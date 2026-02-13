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
    }

    public function boot(): void
    {
        Order::observe(OrderObserver::class);
        Product::observe(ProductObserver::class);
    }
}
