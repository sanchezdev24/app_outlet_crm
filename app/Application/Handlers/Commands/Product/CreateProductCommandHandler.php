<?php

namespace App\Application\Handlers\Commands\Product;

use App\Application\Commands\Product\CreateProductCommand;
use App\Domain\Product\Repositories\ProductRepositoryInterface;
use App\Domain\Product\Entities\Product;
use App\Domain\Product\ValueObjects\Price;
use App\Domain\Product\ValueObjects\Stock;
use App\Domain\Product\ValueObjects\Discount;

class CreateProductCommandHandler
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function handle(CreateProductCommand $command): Product
    {
        $price = new Price($command->price);
        $stock = new Stock($command->stock);
        $discount = null;

        if ($command->discountPercentage !== null) {
            $validUntil = $command->discountValidUntil 
                ? new \DateTime($command->discountValidUntil) 
                : null;
            $discount = new Discount($command->discountPercentage, $validUntil);
        }

        $product = new Product(
            0, // Will be set by repository
            $command->name,
            $command->description,
            $command->sku,
            $price,
            $stock,
            $command->categoryId,
            $command->brandId,
            $command->images,
            $discount
        );

        return $this->productRepository->save($product);
    }
}