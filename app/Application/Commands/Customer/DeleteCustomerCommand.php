<?php

namespace App\Application\Commands\Customer;

class DeleteCustomerCommand
{
    public function __construct(
        public readonly int $id
    ) {}
}