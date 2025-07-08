<?php

namespace App\Application\Commands\Customer;

class CreateCustomerCommand
{
    public function __construct(
        public readonly string $name,
        public readonly string $lastName,
        public readonly string $email,
        public readonly string $phone,
        public readonly string $address,
        public readonly string $type,
        public readonly string $birthDate
    ) {}
}