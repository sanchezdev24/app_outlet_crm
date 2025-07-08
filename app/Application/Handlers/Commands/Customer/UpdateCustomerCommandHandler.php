<?php

// App/Application/Handlers/Commands/Customer/UpdateCustomerCommandHandler.php
namespace App\Application\Handlers\Commands\Customer;

use App\Application\Commands\Customer\UpdateCustomerCommand;
use App\Domain\Customer\Repositories\CustomerRepositoryInterface;
use App\Domain\Customer\Entities\Customer;
use App\Domain\Customer\ValueObjects\CustomerType;
use App\Domain\Customer\ValueObjects\ContactInfo;

class UpdateCustomerCommandHandler
{
    public function __construct(
        private CustomerRepositoryInterface $customerRepository
    ) {}

    public function handle(UpdateCustomerCommand $command): Customer
    {
        // Buscar el cliente existente
        $existingCustomer = $this->customerRepository->findById($command->id);
        
        if (!$existingCustomer) {
            throw new \Exception("Customer with ID {$command->id} not found");
        }

        // Crear los value objects
        $contactInfo = new ContactInfo($command->email, $command->phone, $command->address);
        $customerType = new CustomerType($command->type);
        $birthDate = $command->birthDate ? new \DateTime($command->birthDate) : null;

        // Crear nueva instancia del customer con los datos actualizados
        $updatedCustomer = new Customer(
            $command->id, // Mantener el ID existente
            $command->name,
            $command->lastName,
            $contactInfo,
            $customerType,
            $birthDate,
            $existingCustomer->isActive(), // Mantener el estado actual
            $existingCustomer->getCreatedAt(), // Mantener fecha de creación
            new \DateTime() // Nueva fecha de actualización
        );

        return $this->customerRepository->save($updatedCustomer);
    }
}
