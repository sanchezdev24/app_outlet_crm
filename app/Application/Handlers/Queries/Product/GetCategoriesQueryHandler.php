<?php

namespace App\Application\Handlers\Queries\Product;

use App\Application\Queries\Product\GetCategoriesQuery;
use App\Domain\Product\Repositories\CategoryRepositoryInterface;

class GetCategoriesQueryHandler
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    ) {}

    public function handle(GetCategoriesQuery $query): array
    {
        return $this->categoryRepository->findAll(
            $query->active,
            $query->parentId
        );
    }
}