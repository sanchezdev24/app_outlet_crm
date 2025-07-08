<?php

namespace App\Domain\Product\Repositories;

interface BrandRepositoryInterface
{
    public function findAll(?bool $active = null): array;
    
    public function findById(int $id): ?object;
    
    public function save(object $brand): object;
    
    public function delete(int $id): bool;
}