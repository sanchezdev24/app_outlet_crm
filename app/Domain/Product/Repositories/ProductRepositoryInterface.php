<?php

// App/Domain/Product/Repositories/ProductRepositoryInterface.php
namespace App\Domain\Product\Repositories;

use App\Domain\Product\Entities\Product as DomainProduct;

interface ProductRepositoryInterface
{
    public function findById(int $id): ?DomainProduct;
    
    public function findAll(
        ?string $search = null,
        ?int $categoryId = null,
        ?int $brandId = null,
        ?bool $active = null,
        ?bool $inStock = null,
        int $page = 1,
        int $perPage = 15
    ): array;
    
    public function save(DomainProduct $product): DomainProduct;
    
    public function delete(int $id): bool;
}