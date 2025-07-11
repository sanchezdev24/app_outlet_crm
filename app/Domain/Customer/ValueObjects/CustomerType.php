<?php

namespace App\Domain\Customer\ValueObjects;

use JsonSerializable;

class CustomerType implements JsonSerializable
{
    public const REGULAR = 'regular';
    public const VIP = 'vip';
    public const WHOLESALE = 'wholesale';

    private string $value;

    public function __construct(string $value)
    {
        if (!in_array($value, [self::REGULAR, self::VIP, self::WHOLESALE])) {
            throw new \InvalidArgumentException('Invalid customer type');
        }
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isVip(): bool
    {
        return $this->value === self::VIP;
    }

    public function isWholesale(): bool
    {
        return $this->value === self::WHOLESALE;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    // MÉTODO REQUERIDO para JsonSerializable
    public function jsonSerialize(): mixed
    {
        return $this->value;
    }
}