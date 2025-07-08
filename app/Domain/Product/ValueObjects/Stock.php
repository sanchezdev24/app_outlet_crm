<?php

namespace App\Domain\Product\ValueObjects;

class Stock
{
    private int $quantity;
    private int $minQuantity;

    public function __construct(int $quantity, int $minQuantity = 0)
    {
        if ($quantity < 0) {
            throw new \InvalidArgumentException('Stock quantity cannot be negative');
        }
        if ($minQuantity < 0) {
            throw new \InvalidArgumentException('Minimum stock quantity cannot be negative');
        }
        $this->quantity = $quantity;
        $this->minQuantity = $minQuantity;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getMinQuantity(): int
    {
        return $this->minQuantity;
    }

    public function isAvailable(): bool
    {
        return $this->quantity > 0;
    }

    public function isLowStock(): bool
    {
        return $this->quantity <= $this->minQuantity;
    }

    public function reduce(int $quantity): Stock
    {
        if ($quantity > $this->quantity) {
            throw new \InvalidArgumentException('Cannot reduce stock below zero');
        }
        return new Stock($this->quantity - $quantity, $this->minQuantity);
    }

    public function add(int $quantity): Stock
    {
        return new Stock($this->quantity + $quantity, $this->minQuantity);
    }
}