<?php

// app/Domain/Auth/Entities/User.php
namespace App\Domain\Auth\Entities;

use App\Domain\Auth\ValueObjects\Email;
use App\Domain\Auth\ValueObjects\Password;

class User
{
    private int $id;
    private string $name;
    private Email $email;
    private Password $password;
    private string $role;
    private \DateTime $createdAt;
    private ?\DateTime $updatedAt;

    public function __construct(
        int $id,
        string $name,
        Email $email,
        Password $password,
        string $role = 'user',
        \DateTime $createdAt = null,
        ?\DateTime $updatedAt = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->createdAt = $createdAt ?? new \DateTime();
        $this->updatedAt = $updatedAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function changeName(string $name): void
    {
        $this->name = $name;
        $this->updatedAt = new \DateTime();
    }

    public function changePassword(Password $password): void
    {
        $this->password = $password;
        $this->updatedAt = new \DateTime();
    }
}