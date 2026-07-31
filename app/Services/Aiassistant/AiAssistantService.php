<?php

namespace App\Services\AiAssistant;

class AiAssistantService
{
    public function __construct(
        private StockAnalysisService $stockAnalysisService,
        private OrderAnalysisService $orderAnalysisService,
        private PurchaseSuggestionService $purchaseSuggestionService,
        private ProductionSuggestionService $productionSuggestionService,
        private IntentDetectorService $intentDetector,
        private PromptBuilderService $promptBuilder,
        private ResponseFormatterService $responseFormatter,
        private AIService $aiService, // Nuevo servicio para Gemini
    ) {
    }

    /**
     * Devuelve todo el contexto del negocio.
     */
    public function getBusinessContext(): array
    {
        return [
            'generated_at' => now()->toDateTimeString(),
            'modules' => [
                'stock' => $this->stockAnalysisService->analyze(),
                'orders' => $this->orderAnalysisService->analyze(),
                'purchases' => $this->purchaseSuggestionService->analyze(),
                'production' => $this->productionSuggestionService->analyze(),
            ],
        ];
    }

    /**
     * Datos resumidos para el dashboard.
     */
    public function getDashboardData(): array
    {
        return [
            'stock' => $this->stockAnalysisService->getSummary(),
            'orders' => $this->orderAnalysisService->getSummary(),
            'purchases' => $this->purchaseSuggestionService->getSummary(),
            'production' => $this->productionSuggestionService->getSummary(),
        ];
    }

    /**
     * Obtiene únicamente el análisis de un módulo.
     */
    public function getModule(string $module): array
    {
        return match ($module) {
            'stock' => $this->stockAnalysisService->analyze(),
            'orders' => $this->orderAnalysisService->analyze(),
            'purchases' => $this->purchaseSuggestionService->analyze(),
            'production' => $this->productionSuggestionService->analyze(),
            default => [
                'error' => 'Módulo no encontrado.',
                'available_modules' => ['stock', 'orders', 'purchases', 'production'],
            ],
        };
    }

    /**
     * Punto de entrada para el chatbot con IA integrada.
     */
    public function ask(string $question, string $format = 'html'): array
    {
        try {
            // 1. Detectar intención
            $intent = $this->intentDetector->detect($question);
            
            // 2. Construir prompt inteligente con el contexto del negocio
            $prompt = $this->buildPromptWithContext($question, $intent);
            
            // 3. Llamar a Gemini
            $aiResponse = $this->aiService->generate($prompt);
            
            // 4. Formatear respuesta
            $formattedResponse = $this->responseFormatter->format([
                'response' => $aiResponse,
                'context' => $this->getBusinessContext(),
                'intent' => $intent,
            ], $format);
            
            return [
                'question' => $question,
                'intent' => $intent,
                'prompt' => $prompt,
                'response' => $formattedResponse,
                'format' => $format,
            ];
            
        } catch (\Exception $e) {
            return [
                'error' => 'Error al procesar la pregunta: ' . $e->getMessage(),
                'question' => $question,
            ];
        }
    }

    /**
     * Devuelve un prompt que luego será enviado al modelo de IA.
     */
    public function buildPrompt(string $question): string
    {
        return $this->promptBuilder->build($question);
    }

    /**
     * Construye un prompt con el contexto completo del negocio
     */
    private function buildPromptWithContext(string $question, array $intent): string
    {
        $context = $this->getBusinessContext();
        $contextJson = json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        $intentText = $intent['primary_intent'] ?? 'general';
        $confidence = $intent['confidence'] ?? 0.5;

        return <<<PROMPT
Eres un asistente inteligente especializado en gestión de panaderías y negocios de repostería.

Tu rol es ayudar al dueño/encargado a tomar decisiones basadas en datos reales del negocio.

======================
CONTEXTO DEL NEGOCIO
======================

{$contextJson}

======================
DETALLES DE LA CONSULTA
======================

Pregunta del usuario: {$question}

Intención detectada: {$intentText} (Confianza: {$confidence}%)

======================
INSTRUCCIONES
======================

1. Analiza la pregunta y el contexto proporcionado.
2. Responde de forma clara, concisa y profesional.
3. Si la pregunta requiere datos específicos, extráelos del contexto.
4. Si no encuentras la información, sugiere cómo obtenerla.
5. Ofrece recomendaciones accionables basadas en los datos.
6. Si detectas problemas críticos (stock bajo, pedidos pendientes, etc.), destácalos.
7. Usa emojis para hacer la respuesta más visual y amigable.

Formato de respuesta:
- Usa lenguaje natural y cercano.
- Si hay datos numéricos, preséntalos de forma clara.
- Organiza la información en párrafos cortos.
- Usa viñetas solo si es necesario para listar items.
- Usa negritas (**texto**) para resaltar información importante.

Tu respuesta:
PROMPT;
    }

    /**
     * Simula una respuesta para testing (fallback cuando Gemini no está disponible).
     */
    private function simulateResponse(string $question, array $intent): string
    {
        $module = $intent['module'] ?? 'general';
        $context = $this->getBusinessContext();
        
        if ($module === 'general' || !isset($context['modules'][$module])) {
            return "📊 **Resumen General del Negocio**\n\n" .
                   "Actualmente, el negocio está operando con los siguientes indicadores:\n" .
                   "- 🏭 Producción: " . ($context['modules']['production']['summary']['today']['total_products'] ?? 0) . " productos hoy\n" .
                   "- 📦 Stock: " . ($context['modules']['stock']['summary']['total_inputs'] ?? 0) . " insumos activos\n" .
                   "- 🛒 Pedidos: " . ($context['modules']['orders']['summary']['today']['total_orders'] ?? 0) . " hoy\n" .
                   "- 💰 Ventas: $" . number_format($context['modules']['orders']['summary']['today']['total_revenue'] ?? 0, 2) . "\n\n" .
                   "¿Necesitas información más específica? Puedo ayudarte con stock, pedidos, compras o producción.";
        }
        
        // Respuesta específica del módulo
        $moduleData = $context['modules'][$module];
        
        return "📊 **Análisis de " . ucfirst($module) . "**\n\n" .
               "**Resumen:**\n" .
               $this->formatModuleSummary($moduleData['summary'] ?? []) .
               "\n**Estadísticas clave:**\n" .
               $this->formatModuleStatistics($moduleData['statistics'] ?? []) .
               "\n" . ($moduleData['alerts'] ? "⚠️ **Alertas:** Hay alertas que requieren atención.\n" : "") .
               "\n¿Necesitas más detalles sobre algún aspecto específico?";
    }

    private function formatModuleSummary(array $summary): string
    {
        $output = '';
        foreach ($summary as $key => $value) {
            if (is_array($value)) {
                $output .= "- " . ucfirst(str_replace('_', ' ', $key)) . ":\n";
                foreach ($value as $subKey => $subValue) {
                    $output .= "  • " . ucfirst(str_replace('_', ' ', $subKey)) . ": " . 
                              $this->formatValueForSimulation($subValue) . "\n";
                }
            } else {
                $output .= "- " . ucfirst(str_replace('_', ' ', $key)) . ": " . 
                          $this->formatValueForSimulation($value) . "\n";
            }
        }
        return $output;
    }

    private function formatModuleStatistics(array $statistics): string
    {
        $output = '';
        foreach ($statistics as $key => $value) {
            if (!is_array($value) || empty($value)) {
                $output .= "- " . ucfirst(str_replace('_', ' ', $key)) . ": " . 
                          $this->formatValueForSimulation($value) . "\n";
            }
        }
        return $output;
    }

    /**
     * @param mixed $value
     */
    private function formatValueForSimulation($value): string
    {
        if (is_float($value) || (is_numeric($value) && strpos((string)$value, '.') !== false)) {
            return '$' . number_format($value, 2);
        }
        if (is_int($value)) {
            return number_format($value);
        }
        if (is_bool($value)) {
            return $value ? 'Sí' : 'No';
        }
        if (is_null($value)) {
            return 'N/A';
        }
        return (string) $value;
    }
}