<?php

namespace App\Domain\Product\Entities;

use App\Domain\Product\ValueObjects\Price;
use App\Domain\Product\ValueObjects\Stock;
use App\Domain\Product\ValueObjects\Discount;
use JsonSerializable;

class Product implements JsonSerializable
{
    private int $id;
    private string $name;
    private string $description;
    private string $sku;
    private Price $price;
    private Stock $stock;
    private ?Discount $discount;
    private int $categoryId;
    private int $brandId;
    private array $images;
    private bool $isActive;
    private \DateTime $createdAt;
    private ?\DateTime $updatedAt;

    public function __construct(
        int $id,
        string $name,
        string $description,
        string $sku,
        Price $price,
        Stock $stock,
        int $categoryId,
        int $brandId,
        array $images = [],
        ?Discount $discount = null,
        bool $isActive = true,
        \DateTime $createdAt = null,
        ?\DateTime $updatedAt = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->sku = $sku;
        $this->price = $price;
        $this->stock = $stock;
        $this->categoryId = $categoryId;
        $this->brandId = $brandId;
        $this->images = $images;
        $this->discount = $discount;
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

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function getFinalPrice(): float
    {
        if ($this->discount) {
            return $this->discount->applyTo($this->price);
        }
        return $this->price->getValue();
    }

    public function getStock(): Stock
    {
        return $this->stock;
    }

    public function isInStock(): bool
    {
        return $this->stock->isAvailable();
    }

    public function getCategoryId(): int
    {
        return $this->categoryId;
    }

    public function getBrandId(): int
    {
        return $this->brandId;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function applyDiscount(Discount $discount): void
    {
        $this->discount = $discount;
        $this->updatedAt = new \DateTime();
    }

    public function removeDiscount(): void
    {
        $this->discount = null;
        $this->updatedAt = new \DateTime();
    }

    public function updateStock(Stock $stock): void
    {
        $this->stock = $stock;
        $this->updatedAt = new \DateTime();
    }

    public function reduceStock(int $quantity): void
    {
        $this->stock = $this->stock->reduce($quantity);
        $this->updatedAt = new \DateTime();
    }

    public function addStock(int $quantity): void
    {
        $this->stock = $this->stock->add($quantity);
        $this->updatedAt = new \DateTime();
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

    // Implementar JsonSerializable para serialización correcta
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'sku' => $this->sku,
            'price' => $this->price,
            'stock' => $this->stock,
            'discount' => $this->discount,
            'categoryId' => $this->categoryId,
            'brandId' => $this->brandId,
            'images' => $this->images,
            'isActive' => $this->isActive,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }

    // Método alternativo toArray() si prefieres no usar JsonSerializable
    public function toArray(): array
    {
        return $this->jsonSerialize();
    }
}