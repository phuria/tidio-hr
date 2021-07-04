<?php

declare(strict_types=1);

namespace App\Model;

class SalaryBonusType
{
    private const FIXED = 'fixed';
    private const PERCENTAGE = 'percentage';

    public static function getChoices(): array
    {
        return [
            self::FIXED => 'Fixed',
            self::PERCENTAGE => 'Percentage',
        ];
    }

    public static function FIXED(): self
    {
        return new self(self::FIXED);
    }

    public static function PERCENTAGE(): self
    {
        return new self(self::PERCENTAGE);
    }

    public function __construct(private string $type)
    {
    }

    public function isEquals(self $other): bool
    {
        return $other->type === $this->type;
    }

    public function toString(): string
    {
        return self::getChoices()[$this->type];
    }
}
