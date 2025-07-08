<?php

namespace App\Application\Handlers\Queries\Product;

use App\Application\Queries\Product\GetProductByIdQuery;
use App\Domain\Product\Repositories\ProductRepositoryInterface;
use App\Domain\Product\Product;

class GetProductByIdQueryHandler
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function handle(GetProductByIdQuery $query): ?Product
    {
        return $this->productRepository->findById($query->id);
    }
}