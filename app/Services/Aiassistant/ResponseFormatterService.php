<?php

namespace App\Services\AiAssistant;

class ResponseFormatterService
{
    /**
     * Formatea la respuesta para diferentes salidas.
     */
    public function format(array $response, string $format = 'html'): string
    {
        return match ($format) {
            'html' => $this->formatForHtml($response),
            'markdown' => $this->formatForMarkdown($response),
            'json' => $this->formatForJson($response),
            'cli' => $this->formatForCli($response),
            default => $this->formatForHtml($response),
        };
    }

    /**
     * Formatea para HTML (vistas) - SOLO LA RESPUESTA DE LA IA
     */
    private function formatForHtml(array $response): string
    {
        // Si hay error, mostrarlo
        if (isset($response['error'])) {
            return $this->wrapInHtml($response['error'], 'error');
        }

        // Solo mostrar la respuesta de la IA
        if (isset($response['response'])) {
            // Convertir saltos de línea a <br>
            $content = nl2br($response['response']);
            return $this->wrapInHtml($content, 'response');
        }

        return 'No se recibió respuesta.';
    }

    /**
     * Formatea para Markdown - SOLO LA RESPUESTA DE LA IA
     */
    private function formatForMarkdown(array $response): string
    {
        if (isset($response['error'])) {
            return "❌ **Error:** {$response['error']}";
        }
        
        if (isset($response['response'])) {
            return "## 🤖 Respuesta del Asistente\n\n" . $response['response'] . "\n\n";
        }
        
        return "No se recibió respuesta.";
    }

    /**
     * Formatea para JSON - INCLUYE TODO (para depuración)
     */
    private function formatForJson(array $response): string
    {
        return json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Formatea para CLI/consola - SOLO LA RESPUESTA DE LA IA
     */
    private function formatForCli(array $response): string
    {
        if (isset($response['error'])) {
            return "❌ ERROR: " . $response['error'];
        }
        
        if (isset($response['response'])) {
            return "🤖 Asistente:\n" . $response['response'] . "\n\n";
        }
        
        return "No se recibió respuesta.";
    }

    private function wrapInHtml(string $content, string $type): string
    {
        $class = match($type) {
            'error' => 'text-red-400',
            'response' => 'text-gray-300',
            default => '',
        };
        
        return "<div class='{$class}'>{$content}</div>";
    }

    private function formatValueForHtml($value): string
    {
        if (is_numeric($value) && is_float($value)) {
            return '$' . number_format($value, 2);
        }
        if (is_bool($value)) {
            return $value ? '✅ Sí' : '❌ No';
        }
        return (string) $value;
    }
}