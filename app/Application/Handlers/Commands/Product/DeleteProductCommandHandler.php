<?php

// App/Application/Handlers/Commands/Product/DeleteProductCommandHandler.php
namespace App\Application\Handlers\Commands\Product;

use App\Application\Commands\Product\DeleteProductCommand;
use App\Domain\Product\Repositories\ProductRepositoryInterface;

class DeleteProductCommandHandler
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function handle(DeleteProductCommand $command): bool
    {
        // Verificar que el producto existe
        $product = $this->productRepository->findById($command->id);
        
        if (!$product) {
            throw new \Exception("Product with ID {$command->id} not found");
        }

        // Eliminar el producto
        return $this->productRepository->delete($command->id);
    }
}