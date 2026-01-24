<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\CategoryRepository;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\ProductRepository;
use App\Repositories\Supplier\SupplierRepository;
use App\Repositories\Supplier\SupplierRepositoryInterface;

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
    }

    public function boot(): void
    {
        //
    }
}
