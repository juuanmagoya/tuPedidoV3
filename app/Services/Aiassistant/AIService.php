<?php

namespace App\Services\AiAssistant;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    public function generate(string $prompt): string
    {
        $apiKey = env('GROQ_API_KEY');
        
        if (!$apiKey) {
            Log::warning('GROQ_API_KEY no configurada en .env');
            return "❌ Error: GROQ_API_KEY no configurada";
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->withOptions([
                    'verify' => false, // 🔴 Deshabilita la verificación SSL (SOLO LOCAL)
                ])
                ->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'llama-3.3-70b-versatile',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 500,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? 'No se pudo generar respuesta.';
            }

            Log::error('Error Groq: ' . $response->status() . ' - ' . $response->body());
            return "❌ Error en la API de Groq: " . $response->status();

        } catch (\Exception $e) {
            Log::error('Excepción Groq: ' . $e->getMessage());
            return "❌ Error: " . $e->getMessage();
        }
    }
}