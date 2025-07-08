<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Auth\Repositories\UserRepositoryInterface;
use App\Domain\Customer\Repositories\CustomerRepositoryInterface;
use App\Domain\Product\Repositories\ProductRepositoryInterface;
use App\Domain\Product\Repositories\CategoryRepositoryInterface;
use App\Domain\Product\Repositories\BrandRepositoryInterface;
use App\Domain\Sale\Repositories\SaleRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentUserRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentCustomerRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentProductRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentCategoryRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentBrandRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentSaleRepository;

class DomainServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind repositories
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, EloquentCustomerRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, EloquentProductRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, EloquentCategoryRepository::class);
        $this->app->bind(BrandRepositoryInterface::class, EloquentBrandRepository::class);
        $this->app->bind(SaleRepositoryInterface::class, EloquentSaleRepository::class);
    }

    public function boot(): void
    {
        //
    }
}