<?php

namespace App\Services\AiAssistant;

class PromptBuilderService
{
    public function __construct(
        private IntentDetectorService $intentDetector,
        // Eliminamos AiAssistantService para romper la dependencia circular
    ) {
    }

    /**
     * Construye un prompt inteligente basado en la intención detectada.
     */
    public function build(string $question): string
    {
        $intent = $this->intentDetector->detect($question);
        $entities = $this->intentDetector->extractEntities($question);
        
        // Ya no necesitamos el contexto completo para el prompt inicial
        // Solo usamos la intención y las entidades detectadas

        return $this->buildPromptTemplate($question, $intent, $entities);
    }

    private function buildPromptTemplate(string $question, array $intent, array $entities): string
    {
        $intentText = $intent['primary_intent'] ?? 'general';
        $confidence = $intent['confidence'] ?? 0.5;

        $entitiesText = empty($entities) ? 'No se detectaron entidades específicas.' : json_encode($entities, JSON_PRETTY_PRINT);

        return <<<PROMPT
Eres un asistente inteligente especializado en gestión de panaderías y negocios de repostería.

Tu rol es ayudar al dueño/encargado a tomar decisiones basadas en datos reales del negocio.

======================
DETALLES DE LA CONSULTA
======================

Pregunta del usuario: {$question}

Intención detectada: {$intentText} (Confianza: {$confidence}%)

Entidades extraídas:
{$entitiesText}

======================
INSTRUCCIONES
======================

1. Analiza la pregunta del usuario.
2. Responde de forma clara, concisa y profesional.
3. Ofrece recomendaciones accionables basadas en el contexto de una panadería.
4. Si detectas problemas críticos (stock bajo, pedidos pendientes, etc.), destácalos.

Formato de respuesta:
- Usa lenguaje natural y cercano.
- Organiza la información en párrafos cortos.
- Usa viñetas solo si es necesario para listar items.

Tu respuesta:
PROMPT;
    }
}