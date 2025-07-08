<?php

namespace App\Application\Queries\Customer;

class GetCustomerByIdQuery
{
    public function __construct(
        public readonly int $id
    ) {}
}