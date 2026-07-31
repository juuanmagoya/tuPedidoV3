<?php

namespace App\Http\Controllers;

use App\Services\AiAssistant\AiAssistantService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class AiAssistantController extends Controller
{
    public function __construct(
        private AiAssistantService $aiAssistantService
    ) {
    }

    /**
     * Pantalla principal del asistente.
     */
    public function index(): View
    {
        return view('ai-assistant.index', [
            'dashboard' => $this->aiAssistantService->getDashboardData(),
        ]);
    }

    /**
     * Muestra el contexto completo del negocio.
     */
    public function businessContext(): View
    {
        return view('ai-assistant.business-context', [
            'context' => $this->aiAssistantService->getBusinessContext(),
        ]);
    }

    /**
     * Muestra un módulo específico.
     */
    public function module(string $module): View
    {
        return view('ai-assistant.module', [
            'module' => $this->aiAssistantService->getModule($module),
        ]);
    }

    /**
     * Recibe la pregunta del usuario para el chat.
     * Siempre devuelve JSON para el chat interactivo.
     */
    public function ask(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:1000'],
            'format' => ['sometimes', 'in:html,markdown,json'],
        ]);

        $format = $validated['format'] ?? 'html';

        try {
            $result = $this->aiAssistantService->ask(
                $validated['question'],
                $format
            );

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Vista para depurar el prompt que se enviará a la IA.
     */
    public function prompt(Request $request): View|JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:1000'],
        ]);

        try {
            $prompt = $this->aiAssistantService->buildPrompt(
                $validated['question']
            );

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'question' => $validated['question'],
                        'prompt' => $prompt,
                    ],
                ]);
            }

            return view('ai-assistant.prompt', [
                'question' => $validated['question'],
                'prompt' => $prompt,
            ]);

        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => $e->getMessage(),
                ], 500);
            }

            return back()
                ->withErrors(['error' => 'Error al generar el prompt: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Obtiene el dashboard del asistente en formato JSON.
     */
    public function dashboardJson(): JsonResponse
    {
        try {
            $dashboard = $this->aiAssistantService->getDashboardData();

            return response()->json([
                'success' => true,
                'data' => $dashboard,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtiene un módulo específico en formato JSON.
     */
    public function moduleJson(string $module): JsonResponse
    {
        try {
            $moduleData = $this->aiAssistantService->getModule($module);

            return response()->json([
                'success' => true,
                'data' => $moduleData,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtiene el contexto completo del negocio en JSON.
     */
    public function contextJson(): JsonResponse
    {
        try {
            $context = $this->aiAssistantService->getBusinessContext();

            return response()->json([
                'success' => true,
                'data' => $context,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtiene alertas de todos los módulos.
     */
    public function alerts(): JsonResponse
    {
        try {
            $context = $this->aiAssistantService->getBusinessContext();
            $alerts = [];

            foreach ($context['modules'] as $module => $data) {
                if (isset($data['alerts'])) {
                    $alerts[$module] = $data['alerts'];
                }
            }

            return response()->json([
                'success' => true,
                'data' => $alerts,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Método para pruebas rápidas del asistente.
     */
    public function test(Request $request): View
    {
        $testQuestions = [
            '¿Cómo está el stock hoy?',
            '¿Cuántos pedidos tenemos pendientes?',
            '¿Qué insumos están críticos?',
            '¿Cómo va la producción este mes?',
            'Dame un resumen general del negocio',
        ];

        $selectedQuestion = $request->get('question', $testQuestions[0]);
        
        try {
            $result = $this->aiAssistantService->ask($selectedQuestion, 'html');
            
            return view('ai-assistant.test', [
                'testQuestions' => $testQuestions,
                'selectedQuestion' => $selectedQuestion,
                'result' => $result,
            ]);
        } catch (\Exception $e) {
            return view('ai-assistant.test', [
                'testQuestions' => $testQuestions,
                'selectedQuestion' => $selectedQuestion,
                'error' => $e->getMessage(),
            ]);
        }
    }
}