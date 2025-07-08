<?php

namespace App\Domain\Product\Repositories;

interface CategoryRepositoryInterface
{
    public function findAll(?bool $active = null, ?int $parentId = null): array;
    
    public function findById(int $id): ?object;
    
    public function save(object $category): object;
    
    public function delete(int $id): bool;
}