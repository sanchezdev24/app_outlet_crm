<?php

namespace App\Application\Queries\Customer;

class GetCustomersQuery
{
    public function __construct(
        public readonly ?string $search = null,
        public readonly ?string $type = null,
        public readonly ?bool $active = null,
        public readonly int $page = 1,
        public readonly int $perPage = 15
    ) {}
}