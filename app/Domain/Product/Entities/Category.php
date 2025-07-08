<?php

namespace App\Domain\Product\Entities;

class Category
{
    private int $id;
    private string $name;
    private string $description;
    private ?int $parentId;
    private bool $isActive;
    private \DateTime $createdAt;
    private ?\DateTime $updatedAt;

    public function __construct(
        int $id,
        string $name,
        string $description,
        ?int $parentId = null,
        bool $isActive = true,
        \DateTime $createdAt = null,
        ?\DateTime $updatedAt = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->parentId = $parentId;
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

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function hasParent(): bool
    {
        return $this->parentId !== null;
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

    public function updateName(string $name): void
    {
        $this->name = $name;
        $this->updatedAt = new \DateTime();
    }

    public function updateDescription(string $description): void
    {
        $this->description = $description;
        $this->updatedAt = new \DateTime();
    }
}