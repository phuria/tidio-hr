<?php

declare(strict_types=1);

namespace App\Model;

class Payout
{
    public function __construct(
        private Employee $employee,
        private Month $paymentMonth,
        private Money $salaryBonus,
    ) {
    }

    public function getSalaryBonus(): Money
    {
        return $this->salaryBonus;
    }

    public function getFinalSalary(): Money
    {
        return $this->employee->getBaseSalary()->add($this->salaryBonus);
    }
}
