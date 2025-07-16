<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Product\Repositories\BrandRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Models\Brand;

class EloquentBrandRepository implements BrandRepositoryInterface
{
    public function findAll(?bool $active = null): array
    {
        $query = Brand::query();
        
        if ($active !== null) {
            $query->where('is_active', $active);
        }
        
        return $query->get()->toArray();
    }
    
    public function findById(int $id): ?object
    {
        return Brand::find($id);
    }
    
    public function save(object $brand): object
    {
        if (isset($brand->id)) {
            $existingBrand = Brand::find($brand->id);
            if ($existingBrand) {
                $existingBrand->update((array) $brand);
                return $existingBrand;
            }
        }
        
        return Brand::create((array) $brand);
    }
    
    public function delete(int $id): bool
    {
        $brand = Brand::find($id);
        
        if (!$brand) {
            return false;
        }
        
        return $brand->delete();
    }
}