<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Customer\Entities\Customer as DomainCustomer;
use App\Domain\Customer\Repositories\CustomerRepositoryInterface;
use App\Domain\Customer\ValueObjects\CustomerType;
use App\Domain\Customer\ValueObjects\ContactInfo;
use App\Infrastructure\Persistence\Eloquent\Models\Customer as EloquentCustomer;

class EloquentCustomerRepository implements CustomerRepositoryInterface
{
    public function __construct(
        private EloquentCustomer $model
    ) {}

    public function findById(int $id): ?DomainCustomer
    {
        $customer = $this->model->find($id);
        if (!$customer) {
            return null;
        }

        return $this->toDomainEntity($customer);
    }

    public function findAll(
        ?string $search = null,
        ?string $type = null,
        ?bool $active = null,
        int $page = 1,
        int $perPage = 15
    ): array {
        $query = $this->model->newQuery();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($type) {
            $query->where('type', $type);
        }

        if ($active !== null) {
            $query->where('is_active', $active);
        }

        $total = $query->count();
        $customers = $query->offset(($page - 1) * $perPage)
                          ->limit($perPage)
                          ->get();

        $domainCustomers = $customers->map(function ($customer) {
            return $this->toDomainEntity($customer);
        });

        return [
            'data' => $domainCustomers->toArray(),
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage)
        ];
    }

    public function save(DomainCustomer $customer): DomainCustomer
    {
        $eloquentCustomer = $this->model->updateOrCreate(
            ['id' => $customer->getId()],
            [
                'name' => $customer->getName(),
                'last_name' => $customer->getLastName(),
                'email' => $customer->getContactInfo()->getEmail(),
                'phone' => $customer->getContactInfo()->getPhone(),
                'address' => $customer->getContactInfo()->getAddress(),
                'type' => $customer->getType()->getValue(),
                'birth_date' => $customer->getBirthDate(),
                'is_active' => $customer->isActive(),
            ]
        );

        return $this->toDomainEntity($eloquentCustomer);
    }

    public function delete(int $id): bool
    {
        return $this->model->destroy($id) > 0;
    }

    public function toDomainEntity(EloquentCustomer $customer): DomainCustomer
    {
        return new DomainCustomer(
            $customer->id,
            $customer->name,
            $customer->last_name,
            new ContactInfo($customer->email, $customer->phone, $customer->address),
            new CustomerType($customer->type),
            $customer->birth_date,
            $customer->is_active,
            $customer->created_at,
            $customer->updated_at
        );
    }
}