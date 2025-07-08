<?php

namespace App\Application\Commands\Auth;

class RegisterCommand
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly string $role = 'user'
    ) {}
}