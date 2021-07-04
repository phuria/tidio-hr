<?php

declare(strict_types=1);

namespace App\Model;

class Department
{
    private ?int $id;

    public function __construct(
        private string $name,
        private string $shortCode,
        private SalaryBonus $salaryBonus
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getShortCode(): string
    {
        return $this->shortCode;
    }

    public function getSalaryBonus(): SalaryBonus
    {
        return $this->salaryBonus;
    }

    public function getSalaryBonusType(): SalaryBonusType
    {
        return $this->salaryBonus->getType();
    }
}
