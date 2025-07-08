<?php

namespace App\Domain\Product\Entities;

class Brand
{
    private int $id;
    private string $name;
    private string $description;
    private ?string $logo;
    private bool $isActive;
    private \DateTime $createdAt;
    private ?\DateTime $updatedAt;

    public function __construct(
        int $id,
        string $name,
        string $description,
        ?string $logo = null,
        bool $isActive = true,
        \DateTime $createdAt = null,
        ?\DateTime $updatedAt = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->logo = $logo;
        $this->isActive = $isActive;
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
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

    public function updateLogo(?string $logo): void
    {
        $this->logo = $logo;
        $this->updatedAt = new \DateTime();
    }
}