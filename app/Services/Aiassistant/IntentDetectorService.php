<?php

namespace App\Services\AiAssistant;

class IntentDetectorService
{
    private const INTENTS = [
        'stock' => [
            'keywords' => ['stock', 'inventario', 'insumo', 'almacén', 'bodega', 'existencia'],
            'module' => 'stock',
        ],
        'orders' => [
            'keywords' => ['pedido', 'orden', 'venta', 'cliente', 'factura'],
            'module' => 'orders',
        ],
        'purchases' => [
            'keywords' => ['compra', 'proveedor', 'suministro', 'adquisición'],
            'module' => 'purchases',
        ],
        'production' => [
            'keywords' => ['producción', 'fabricación', 'elaborar', 'hacer', 'pan', 'pastel'],
            'module' => 'production',
        ],
        'financial' => [
            'keywords' => ['ganancia', 'pérdida', 'balance', 'finanza', 'dinero', 'ingreso', 'gasto'],
            'module' => 'financial',
        ],
        'general' => [
            'keywords' => ['resumen', 'general', 'todo', 'global', 'estado'],
            'module' => 'general',
        ],
    ];

    /**
     * Detecta la intención de la pregunta del usuario.
     */
    public function detect(string $question): array
    {
        $questionLower = strtolower($question);
        $detectedIntents = [];

        foreach (self::INTENTS as $intent => $config) {
            foreach ($config['keywords'] as $keyword) {
                if (str_contains($questionLower, $keyword)) {
                    $detectedIntents[] = [
                        'intent' => $intent,
                        'module' => $config['module'],
                        'confidence' => $this->calculateConfidence($questionLower, $keyword),
                    ];
                }
            }
        }

        // Si no se detecta ninguna intención, usar general
        if (empty($detectedIntents)) {
            return [
                'primary_intent' => 'general',
                'module' => 'general',
                'confidence' => 0.5,
                'all_intents' => [],
            ];
        }

        // Ordenar por confianza y obtener la principal
        usort($detectedIntents, function ($a, $b) {
            return $b['confidence'] <=> $a['confidence'];
        });

        return [
            'primary_intent' => $detectedIntents[0]['intent'],
            'module' => $detectedIntents[0]['module'],
            'confidence' => $detectedIntents[0]['confidence'],
            'all_intents' => $detectedIntents,
        ];
    }

    /**
     * Calcula la confianza basada en la posición y frecuencia de la keyword.
     */
    private function calculateConfidence(string $question, string $keyword): float
    {
        $pos = strpos($question, $keyword);
        $length = strlen($question);

        // Mayor confianza si aparece al principio
        $positionScore = 1 - ($pos / $length);

        // Mayor confianza si hay múltiples coincidencias
        $count = substr_count($question, $keyword);
        $frequencyScore = min($count, 3) / 3;

        return round(($positionScore * 0.6 + $frequencyScore * 0.4), 2);
    }

    /**
     * Extrae entidades de la pregunta.
     */
    public function extractEntities(string $question): array
    {
        $entities = [];

        // Extraer números (cantidades)
        preg_match_all('/\b\d+\b/', $question, $numbers);
        if (!empty($numbers[0])) {
            $entities['numbers'] = array_map('intval', $numbers[0]);
        }

        // Extraer fechas
        $datePatterns = [
            'hoy' => now()->toDateString(),
            'ayer' => now()->subDay()->toDateString(),
            'esta semana' => now()->startOfWeek()->toDateString(),
            'este mes' => now()->startOfMonth()->toDateString(),
        ];

        foreach ($datePatterns as $key => $value) {
            if (str_contains(strtolower($question), $key)) {
                $entities['date_range'] = $value;
                break;
            }
        }

        // Extraer nombres de productos (simplificado)
        preg_match_all('/\b(pan|pastel|galleta|bollo|torta)\b/', strtolower($question), $products);
        if (!empty($products[0])) {
            $entities['products'] = array_unique($products[0]);
        }

        return $entities;
    }
}