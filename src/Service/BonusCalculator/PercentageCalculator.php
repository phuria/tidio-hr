<?php

declare(strict_types=1);

namespace App\Service\BonusCalculator;

use App\Model\Employee;
use App\Model\Money;
use App\Model\Month;
use App\Model\SalaryBonusType;

class PercentageCalculator implements Calculator
{
    public function isSupported(Employee $employee): bool
    {
        return $employee->getSalaryBonusType()->isEquals(SalaryBonusType::PERCENTAGE());
    }

    public function calculate(Month $payoutMonth, Employee $employee): Money
    {
        return $employee->getBaseSalary()->percent(
            $employee->getSalaryBonus()->toPercentage()
        );
    }
}
