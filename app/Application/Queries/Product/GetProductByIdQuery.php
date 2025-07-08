<?php

namespace App\Application\Queries\Product;

class GetProductByIdQuery
{
    public function __construct(
        public readonly int $id
    ) {}
}