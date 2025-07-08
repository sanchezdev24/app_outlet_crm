<?php

namespace App\Domain\Customer\Repositories;

use App\Domain\Customer\Entities\Customer as DomainCustomer;
use App\Infrastructure\Persistence\Eloquent\Models\Customer as EloquentCustomer;

interface CustomerRepositoryInterface
{
    public function findById(int $id): ?DomainCustomer;
    public function findAll(
        ?string $search = null,
        ?string $type = null,
        ?bool $active = null,
        int $page = 1,
        int $perPage = 15
    ): array;
    public function save(DomainCustomer $customer): DomainCustomer;
    public function delete(int $id): bool;
    public function toDomainEntity(EloquentCustomer $id): DomainCustomer;
}