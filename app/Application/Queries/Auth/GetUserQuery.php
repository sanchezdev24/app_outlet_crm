<?php

namespace App\Application\Queries\Auth;

class GetUserQuery
{
    public function __construct(
        public readonly int $id
    ) {}
}