<?php

namespace App\Services\AiAssistant\Contracts;

interface AnalysisServiceInterface
{
    /**
     * Devuelve un análisis completo del módulo.
     *
     * Este método reúne toda la información necesaria para
     * que el asistente de IA pueda responder preguntas
     * relacionadas con el módulo.
     */
    public function analyze(): array;

    /**
     * Devuelve un resumen general del módulo.
     */
    public function getSummary(): array;

    /**
     * Devuelve estadísticas relevantes.
     */
    public function getStatistics(): array;

    /**
     * Devuelve alertas importantes que requieren atención.
     */
    public function getAlerts(): array;

    /**
     * Devuelve recomendaciones generadas por el sistema.
     */
    public function getRecommendations(): array;
}