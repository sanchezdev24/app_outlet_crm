<?php

namespace App\Domain\Customer\ValueObjects;

class ContactInfo
{
    private string $email;
    private string $phone;
    private string $address;

    public function __construct(string $email, string $phone, string $address)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format');
        }
        $this->email = $email;
        $this->phone = $phone;
        $this->address = $address;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getAddress(): string
    {
        return $this->address;
    }
}