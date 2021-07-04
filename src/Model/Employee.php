<?php

declare(strict_types=1);

namespace App\Model;

use DateTimeInterface;

class Employee
{
    private ?int $id;

    public function __construct(
        private string $firstname,
        private string $lastname,
        private Department $department,
        private Money $baseSalary,
        private DateTimeInterface $employedAt,
    ){ }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function toString(): string
    {
        return "{$this->firstname} {$this->lastname}";
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getDepartmentShortCode(): string
    {
        return $this->getDepartment()->getShortCode();
    }

    public function getDepartment(): Department
    {
        return $this->department;
    }

    public function getBaseSalary(): Money
    {
        return $this->baseSalary;
    }

    public function getSalaryBonusType(): SalaryBonusType
    {
        return $this->getDepartment()->getSalaryBonusType();
    }

    public function getSalaryBonus(): SalaryBonus
    {
        return $this->getDepartment()->getSalaryBonus();
    }

    public function getEmployedAt(): DateTimeInterface
    {
        return $this->employedAt;
    }
}
