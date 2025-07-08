<?php

namespace App\Application\Handlers\Queries\Customer;

use App\Application\Queries\Customer\GetCustomerByIdQuery;
use App\Domain\Customer\Repositories\CustomerRepositoryInterface;
use App\Domain\Customer\Customer;

class GetCustomerByIdQueryHandler
{
    public function __construct(
        private CustomerRepositoryInterface $customerRepository
    ) {}

    public function handle(GetCustomerByIdQuery $query): ?Customer
    {
        return $this->customerRepository->findById($query->id);
    }
}