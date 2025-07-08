<?php

namespace App\Application\Handlers\Queries\Product;

use App\Application\Queries\Product\GetBrandsQuery;
use App\Domain\Product\Repositories\BrandRepositoryInterface;

class GetBrandsQueryHandler
{
    public function __construct(
        private BrandRepositoryInterface $brandRepository
    ) {}

    public function handle(GetBrandsQuery $query): array
    {
        return $this->brandRepository->findAll($query->active);
    }
}