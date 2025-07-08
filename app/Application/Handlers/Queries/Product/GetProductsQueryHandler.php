<?php

namespace App\Application\Handlers\Queries\Product;

use App\Application\Queries\Product\GetProductsQuery;
use App\Domain\Product\Repositories\ProductRepositoryInterface;

class GetProductsQueryHandler
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function handle(GetProductsQuery $query): array
    {
        return $this->productRepository->findAll(
            $query->search,
            $query->categoryId,
            $query->brandId,
            $query->active,
            $query->inStock,
            $query->page,
            $query->perPage
        );
    }
}