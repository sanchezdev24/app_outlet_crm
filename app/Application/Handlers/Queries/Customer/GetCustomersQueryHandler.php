<?php

namespace App\Application\Handlers\Queries\Customer;

use App\Application\Queries\Customer\GetCustomersQuery;
use App\Domain\Customer\Repositories\CustomerRepositoryInterface;

class GetCustomersQueryHandler
{
    public function __construct(
        private CustomerRepositoryInterface $customerRepository
    ) {}

    public function handle(GetCustomersQuery $query): array
    {
        return $this->customerRepository->findAll(
            $query->search,
            $query->type,
            $query->active,
            $query->page,
            $query->perPage
        );
    }
}