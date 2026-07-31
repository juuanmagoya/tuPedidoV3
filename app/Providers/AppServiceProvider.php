<?php

namespace App\Providers;

use App\Models\Order;
use App\Observers\OrderObserver;
use App\Models\Product;
use App\Observers\ProductObserver;
use Illuminate\Support\ServiceProvider;
use App\Repositories\CategoryRepository;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\ProductRepository;
use App\Repositories\Supplier\SupplierRepository;
use App\Repositories\Supplier\SupplierRepositoryInterface;
use App\Repositories\Input\InputRepository;
use App\Repositories\Input\InputRepositoryInterface;
use App\Repositories\Production\Contracts\ProductionRepositoryInterface;
use App\Repositories\Production\ProductionRepository;
use App\Repositories\Production\Contracts\ProductionInputRepositoryInterface;
use App\Repositories\Production\ProductionInputRepository;
use App\Repositories\Production\Contracts\ProductionProductRepositoryInterface;
use App\Repositories\Production\ProductionProductRepository;
use App\Repositories\Purchase\PurchaseRepository;
use App\Repositories\Purchase\PurchaseRepositoryInterface;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Services\AiAssistant\AIAssistantService;
use App\Services\AiAssistant\StockAnalysisService;
use App\Services\AiAssistant\OrderAnalysisService;
use App\Services\AiAssistant\PurchaseSuggestionService;
use App\Services\AiAssistant\ProductionSuggestionService;
use App\Services\AiAssistant\IntentDetectorService;
use App\Services\AiAssistant\PromptBuilderService;
use App\Services\AiAssistant\ResponseFormatterService;
use App\Services\AiAssistant\AIService; // <-- Importar el nuevo servicio

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Category
        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );

        // Product
        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class
        );
        
        //Supplier
        $this->app->bind(
            SupplierRepositoryInterface::class,
            SupplierRepository::class
        );

        // Input
        $this->app->bind(
            InputRepositoryInterface::class,
            InputRepository::class
        );

        // Production
        $this->app->bind(
            ProductionRepositoryInterface::class,
            ProductionRepository::class
        );

        // Production Input
        $this->app->bind(
            ProductionInputRepositoryInterface::class,
            ProductionInputRepository::class
        );

        //Production Product
        $this->app->bind(
            ProductionProductRepositoryInterface::class,
            ProductionProductRepository::class
        );

        //Purchase
        $this->app->bind(
            PurchaseRepositoryInterface::class,
            PurchaseRepository::class
        );

        //Order
        $this->app->bind(
            OrderRepositoryInterface::class,
            OrderRepository::class
        );

        // Registrar todos los servicios del asistente IA en el contenedor
        $this->app->singleton(StockAnalysisService::class);
        $this->app->singleton(OrderAnalysisService::class);
        $this->app->singleton(PurchaseSuggestionService::class);
        $this->app->singleton(ProductionSuggestionService::class);
        $this->app->singleton(IntentDetectorService::class);
        $this->app->singleton(ResponseFormatterService::class);
        $this->app->singleton(AIService::class); // <-- Registrar AIService
        
        // PromptBuilderService - ya no depende de AIAssistantService
        $this->app->singleton(PromptBuilderService::class, function ($app) {
            return new PromptBuilderService(
                $app->make(IntentDetectorService::class)
            );
        });

        // Registrar el servicio principal del asistente IA
        $this->app->singleton(AIAssistantService::class, function ($app) {
            return new AIAssistantService(
                $app->make(StockAnalysisService::class),
                $app->make(OrderAnalysisService::class),
                $app->make(PurchaseSuggestionService::class),
                $app->make(ProductionSuggestionService::class),
                $app->make(IntentDetectorService::class),
                $app->make(PromptBuilderService::class),
                $app->make(ResponseFormatterService::class),
                $app->make(AIService::class) // <-- Pasar AIService como 8vo argumento
            );
        });
    }

    public function boot(): void
    {
        Order::observe(OrderObserver::class);
        Product::observe(ProductObserver::class);
    }
}