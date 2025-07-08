<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use League\Tactician\CommandBus as TacticianCommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;
use App\Application\Services\CommandBus;
use App\Application\Commands\Auth\LoginCommand;
use App\Application\Commands\Auth\RegisterCommand;
use App\Application\Commands\Customer\CreateCustomerCommand;
use App\Application\Commands\Customer\UpdateCustomerCommand;
use App\Application\Commands\Customer\DeleteCustomerCommand;
use App\Application\Commands\Product\CreateProductCommand;
use App\Application\Commands\Product\UpdateProductCommand;
use App\Application\Commands\Product\DeleteProductCommand;
use App\Application\Handlers\Commands\Auth\LoginCommandHandler;
use App\Application\Handlers\Commands\Auth\RegisterCommandHandler;
use App\Application\Handlers\Commands\Customer\CreateCustomerCommandHandler;
use App\Application\Handlers\Commands\Customer\UpdateCustomerCommandHandler;
use App\Application\Handlers\Commands\Customer\DeleteCustomerCommandHandler;
use App\Application\Handlers\Commands\Product\CreateProductCommandHandler;
use App\Application\Handlers\Commands\Product\UpdateProductCommandHandler;
use App\Application\Handlers\Commands\Product\DeleteProductCommandHandler;

class CommandBusServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CommandBus::class, function ($app) {
            $locator = new InMemoryLocator();
            
            // Register command handlers
            $locator->addHandler($app->make(LoginCommandHandler::class), LoginCommand::class);
            $locator->addHandler($app->make(RegisterCommandHandler::class), RegisterCommand::class);
            $locator->addHandler($app->make(CreateCustomerCommandHandler::class), CreateCustomerCommand::class);
            $locator->addHandler($app->make(UpdateCustomerCommandHandler::class), UpdateCustomerCommand::class);
            $locator->addHandler($app->make(DeleteCustomerCommandHandler::class), DeleteCustomerCommand::class);
            $locator->addHandler($app->make(CreateProductCommandHandler::class), CreateProductCommand::class);
            $locator->addHandler($app->make(UpdateProductCommandHandler::class), UpdateProductCommand::class);
            $locator->addHandler($app->make(DeleteProductCommandHandler::class), DeleteProductCommand::class);

            $handlerMiddleware = new CommandHandlerMiddleware(
                new ClassNameExtractor(),
                $locator,
                new HandleInflector()
            );

            $commandBus = new TacticianCommandBus([$handlerMiddleware]);
            
            return new CommandBus($commandBus);
        });
    }

    public function boot(): void
    {
        //
    }
}