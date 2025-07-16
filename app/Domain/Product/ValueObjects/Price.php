<?php

namespace App\Domain\Product\ValueObjects;
use JsonSerializable;

class Price implements JsonSerializable
{
    private float $value;

    public function __construct(float $value)
    {
        if ($value < 0) {
            throw new \InvalidArgumentException('Price cannot be negative');
        }
        $this->value = $value;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getFormattedValue(): string
    {
        return '$' . number_format($this->value, 2);
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    // MÉTODO REQUERIDO para JsonSerializable
    public function jsonSerialize(): mixed
    {
        return $this->value;
    }
}