<?php

namespace App\Application\Queries\Product;

class GetProductsQuery
{
    public function __construct(
        public readonly ?string $search = null,
        public readonly ?int $categoryId = null,
        public readonly ?int $brandId = null,
        public readonly ?bool $active = null,
        public readonly ?bool $inStock = null,
        public readonly int $page = 1,
        public readonly int $perPage = 15
    ) {}
}