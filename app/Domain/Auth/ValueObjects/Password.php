<?php

namespace App\Domain\Auth\ValueObjects;

class Password
{
    private string $hashedValue;

    public function __construct(string $plainPassword)
    {
        if (strlen($plainPassword) < 8) {
            throw new \InvalidArgumentException('Password must be at least 8 characters long');
        }
        $this->hashedValue = password_hash($plainPassword, PASSWORD_DEFAULT);
    }

    public static function fromHash(string $hashedPassword): self
    {
        $instance = new self('temporary');
        $instance->hashedValue = $hashedPassword;
        return $instance;
    }

    public function getHashedValue(): string
    {
        return $this->hashedValue;
    }

    public function verify(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->hashedValue);
    }
}