<?php

namespace App\Application\Queries\Product;

class GetBrandsQuery
{
    public function __construct(
        public readonly ?bool $active = null
    ) {}
}