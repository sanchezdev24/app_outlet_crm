<?php

//namespace App\Infrastructure\Repositories\Product;
namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Product\Repositories\CategoryRepositoryInterface;
use App\Infrastructure\Models\Category;

class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    public function findAll(?bool $active = null, ?int $parentId = null): array
    {
        $query = Category::query();
        
        if ($active !== null) {
            $query->where('active', $active);
        }
        
        if ($parentId !== null) {
            $query->where('parent_id', $parentId);
        }
        
        return $query->get()->toArray();
    }
    
    public function findById(int $id): ?object
    {
        return Category::find($id);
    }
    
    public function save(object $category): object
    {
        if (isset($category->id)) {
            $existingCategory = Category::find($category->id);
            if ($existingCategory) {
                $existingCategory->update((array) $category);
                return $existingCategory;
            }
        }
        
        return Category::create((array) $category);
    }
    
    public function delete(int $id): bool
    {
        $category = Category::find($id);
        
        if (!$category) {
            return false;
        }
        
        return $category->delete();
    }
}