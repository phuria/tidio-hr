<?php

declare(strict_types=1);

namespace App\Model;

class SalaryBonus
{
    public static function percentage(Percentage $percentage): self
    {
        return new SalaryBonus(SalaryBonusType::PERCENTAGE(), (string) $percentage->getValue());
    }

    public static function fixed(Money $money): self
    {
        return new SalaryBonus(SalaryBonusType::FIXED(), $money->getValue());
    }

    private function __construct(private SalaryBonusType $type, private string $value)
    {
    }

    public function getType(): SalaryBonusType
    {
        return $this->type;
    }

    public function toPercentage(): Percentage
    {
        return new Percentage((int) $this->value);
    }

    public function toMoney(): Money
    {
        return new Money($this->value);
    }
}
