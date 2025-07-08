<?php

namespace App\Application\Commands\Product;

class DeleteProductCommand
{
    public function __construct(
        public readonly int $id
    ) {}
}