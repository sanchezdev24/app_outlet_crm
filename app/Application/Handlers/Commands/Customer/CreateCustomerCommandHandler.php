<?php

namespace App\Application\Handlers\Commands\Customer;

use App\Application\Commands\Customer\CreateCustomerCommand;
use App\Domain\Customer\Repositories\CustomerRepositoryInterface;
use App\Domain\Customer\Entities\Customer;
use App\Domain\Customer\ValueObjects\CustomerType;
use App\Domain\Customer\ValueObjects\ContactInfo;

class CreateCustomerCommandHandler
{
    public function __construct(
        private CustomerRepositoryInterface $customerRepository
    ) {}

    public function handle(CreateCustomerCommand $command): Customer
    {
        $contactInfo = new ContactInfo($command->email, $command->phone, $command->address);
        $customerType = new CustomerType($command->type);
        $birthDate = new \DateTime($command->birthDate);

        $customer = new Customer(
            0, // Will be set by repository
            $command->name,
            $command->lastName,
            $contactInfo,
            $customerType,
            $birthDate
        );

        return $this->customerRepository->save($customer);
    }
}