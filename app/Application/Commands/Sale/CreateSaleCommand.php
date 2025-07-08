<?php

namespace App\Application\Commands\Sale;

class CreateSaleCommand
{
    public function __construct(
        public readonly int $customerId,
        public readonly array $items, // [['product_id' => 1, 'quantity' => 2, 'price' => 100.50]]
        public readonly string $paymentMethod,
        public readonly ?float $discount = null,
        public readonly ?string $notes = null
    ) {}
}