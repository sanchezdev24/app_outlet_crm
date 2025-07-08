<?php

namespace App\Application\Queries\Sale;

class GetSalesQuery
{
    public function __construct(
        public readonly ?int $customerId = null,
        public readonly ?string $status = null,
        public readonly ?string $dateFrom = null,
        public readonly ?string $dateTo = null,
        public readonly int $page = 1,
        public readonly int $perPage = 15
    ) {}
}