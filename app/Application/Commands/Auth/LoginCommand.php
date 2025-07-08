<?php

namespace App\Application\Commands\Auth;

class LoginCommand
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly bool $remember = false
    ) {}
}