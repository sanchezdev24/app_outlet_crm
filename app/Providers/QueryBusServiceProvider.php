<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use League\Tactician\CommandBus as TacticianCommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use App\Application\Services\QueryBus;
use App\Application\Queries\Customer\GetCustomersQuery;
use App\Application\Queries\Customer\GetCustomerByIdQuery;
use App\Application\Queries\Product\GetProductsQuery;
use App\Application\Queries\Product\GetProductByIdQuery;
use App\Application\Queries\Product\GetCategoriesQuery;
use App\Application\Queries\Product\GetBrandsQuery;
use App\Application\Handlers\Queries\Customer\GetCustomersQueryHandler;
use App\Application\Handlers\Queries\Customer\GetCustomerByIdQueryHandler;
use App\Application\Handlers\Queries\Product\GetProductsQueryHandler;
use App\Application\Handlers\Queries\Product\GetProductByIdQueryHandler;
use App\Application\Handlers\Queries\Product\GetCategoriesQueryHandler;
use App\Application\Handlers\Queries\Product\GetBrandsQueryHandler;

class QueryBusServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(QueryBus::class, function ($app) {
            $locator = new InMemoryLocator();
            
            // Register query handlers
            $locator->addHandler($app->make(GetCustomersQueryHandler::class), GetCustomersQuery::class);
            $locator->addHandler($app->make(GetCustomerByIdQueryHandler::class), GetCustomerByIdQuery::class);
            $locator->addHandler($app->make(GetProductsQueryHandler::class), GetProductsQuery::class);
            $locator->addHandler($app->make(GetProductByIdQueryHandler::class), GetProductByIdQuery::class);
            $locator->addHandler($app->make(GetCategoriesQueryHandler::class), GetCategoriesQuery::class);
            $locator->addHandler($app->make(GetBrandsQueryHandler::class), GetBrandsQuery::class);

            $handlerMiddleware = new CommandHandlerMiddleware(
                new ClassNameExtractor(),
                $locator,
                new HandleInflector()
            );

            $queryBus = new TacticianCommandBus([$handlerMiddleware]);
            
            return new QueryBus($queryBus);
        });
    }

    public function boot(): void
    {
        //
    }
}