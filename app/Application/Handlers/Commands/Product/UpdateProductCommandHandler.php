<?php

// App/Application/Handlers/Commands/Product/UpdateProductCommandHandler.php
namespace App\Application\Handlers\Commands\Product;

use App\Application\Commands\Product\UpdateProductCommand;
use App\Domain\Product\Repositories\ProductRepositoryInterface;
use App\Domain\Product\Entities\Product;
use App\Domain\Product\ValueObjects\Price;
use App\Domain\Product\ValueObjects\Stock;
use App\Domain\Product\ValueObjects\Discount;

class UpdateProductCommandHandler
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function handle(UpdateProductCommand $command): Product
    {
        // Buscar el producto existente
        $existingProduct = $this->productRepository->findById($command->id);
        
        if (!$existingProduct) {
            throw new \Exception("Product with ID {$command->id} not found");
        }

        // Crear los value objects
        $price = new Price($command->price);
        $stock = new Stock($command->stock, $existingProduct->getStock()->getMinQuantity());
        
        $discount = null;
        if ($command->discountPercentage) {
            $validUntil = $command->discountValidUntil 
                ? new \DateTime($command->discountValidUntil)
                : null;
            $discount = new Discount($command->discountPercentage, $validUntil);
        }

        // Crear nueva instancia del producto con los datos actualizados
        $updatedProduct = new Product(
            $command->id, // Mantener el ID existente
            $command->name,
            $command->description,
            $command->sku,
            $price,
            $stock,
            $command->categoryId,
            $command->brandId,
            $command->images,
            $discount,
            $existingProduct->isActive(), // Mantener el estado actual
            $existingProduct->getCreatedAt(), // Mantener fecha de creación
            new \DateTime() // Nueva fecha de actualización
        );

        return $this->productRepository->save($updatedProduct);
    }
}