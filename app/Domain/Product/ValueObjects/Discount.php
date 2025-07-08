<?php

namespace App\Domain\Product\ValueObjects;

class Discount
{
    private float $percentage;
    private ?\DateTime $validUntil;

    public function __construct(float $percentage, ?\DateTime $validUntil = null)
    {
        if ($percentage < 0 || $percentage > 100) {
            throw new \InvalidArgumentException('Discount percentage must be between 0 and 100');
        }
        $this->percentage = $percentage;
        $this->validUntil = $validUntil;
    }

    public function getPercentage(): float
    {
        return $this->percentage;
    }

    public function getValidUntil(): ?\DateTime
    {
        return $this->validUntil;
    }

    public function isValid(): bool
    {
        if (!$this->validUntil) {
            return true;
        }
        return new \DateTime() <= $this->validUntil;
    }

    public function applyTo(Price $price): float
    {
        if (!$this->isValid()) {
            return $price->getValue();
        }
        return $price->getValue() * (1 - $this->percentage / 100);
    }
}