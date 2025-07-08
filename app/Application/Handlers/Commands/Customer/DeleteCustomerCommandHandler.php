<?php

// App/Application/Handlers/Commands/Customer/DeleteCustomerCommandHandler.php
namespace App\Application\Handlers\Commands\Customer;

use App\Application\Commands\Customer\DeleteCustomerCommand;
use App\Domain\Customer\Repositories\CustomerRepositoryInterface;

class DeleteCustomerCommandHandler
{
    public function __construct(
        private CustomerRepositoryInterface $customerRepository
    ) {}

    public function handle(DeleteCustomerCommand $command): bool
    {
        // Verificar que el cliente existe
        $customer = $this->customerRepository->findById($command->id);
        
        if (!$customer) {
            throw new \Exception("Customer with ID {$command->id} not found");
        }

        // Eliminar el cliente
        return $this->customerRepository->delete($command->id);
    }
}