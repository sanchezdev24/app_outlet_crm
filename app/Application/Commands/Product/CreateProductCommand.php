<?php

namespace App\Application\Commands\Product;

class CreateProductCommand
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly string $sku,
        public readonly float $price,
        public readonly int $stock,
        public readonly int $categoryId,
        public readonly int $brandId,
        public readonly array $images = [],
        public readonly ?float $discountPercentage = null,
        public readonly ?string $discountValidUntil = null
    ) {}
}