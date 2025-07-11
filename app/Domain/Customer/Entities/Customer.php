<?php

namespace App\Domain\Customer\Entities;

use App\Domain\Customer\ValueObjects\CustomerType;
use App\Domain\Customer\ValueObjects\ContactInfo;
use JsonSerializable;

class Customer implements JsonSerializable
{
    private int $id;
    private string $name;
    private string $lastName;
    private ContactInfo $contactInfo;
    private CustomerType $type;
    private \DateTime $birthDate;
    private bool $isActive;
    private \DateTime $createdAt;
    private ?\DateTime $updatedAt;

    public function __construct(
        int $id,
        string $name,
        string $lastName,
        ContactInfo $contactInfo,
        CustomerType $type,
        \DateTime $birthDate,
        bool $isActive = true,
        \DateTime $createdAt = null,
        ?\DateTime $updatedAt = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->lastName = $lastName;
        $this->contactInfo = $contactInfo;
        $this->type = $type;
        $this->birthDate = $birthDate;
        $this->isActive = $isActive;
        $this->createdAt = $createdAt ?? new \DateTime();
        $this->updatedAt = $updatedAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFullName(): string
    {
        return $this->name . ' ' . $this->lastName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getContactInfo(): ContactInfo
    {
        return $this->contactInfo;
    }

    public function getType(): CustomerType
    {
        return $this->type;
    }

    public function getBirthDate(): \DateTime
    {
        return $this->birthDate;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function activate(): void
    {
        $this->isActive = true;
        $this->updatedAt = new \DateTime();
    }

    public function deactivate(): void
    {
        $this->isActive = false;
        $this->updatedAt = new \DateTime();
    }

    public function updateContactInfo(ContactInfo $contactInfo): void
    {
        $this->contactInfo = $contactInfo;
        $this->updatedAt = new \DateTime();
    }

    public function changeType(CustomerType $type): void
    {
        $this->type = $type;
        $this->updatedAt = new \DateTime();
    }

    // Implementar JsonSerializable para serialización correcta
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'last_name' => $this->lastName,
            'full_name' => $this->name . ' ' . $this->lastName,
            'email' => $this->contactInfo->getEmail(),
            'phone' => $this->contactInfo->getPhone(),
            'address' => $this->contactInfo->getAddress(),
            'type' => $this->type->getValue(),
            'birth_date' => $this->birthDate?->format('Y-m-d'),
            'is_active' => $this->isActive,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }

    // Método alternativo toArray() si prefieres no usar JsonSerializable
    public function toArray(): array
    {
        return $this->jsonSerialize();
    }
}