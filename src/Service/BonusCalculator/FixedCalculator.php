<?php

namespace App\Service\BonusCalculator;

use App\Model\Employee;
use App\Model\Money;
use App\Model\Month;
use App\Model\SalaryBonusType;

class FixedCalculator implements Calculator
{
    private const MAX_YEARS_BONUS = 10;

    public function isSupported(Employee $employee): bool
    {
        return $employee->getSalaryBonusType()->isEquals(SalaryBonusType::FIXED());
    }

    public function calculate(Month $payoutMonth, Employee $employee): Money
    {
        $employmentMonth = Month::fromDateTime($employee->getEmployedAt());

        if ($employmentMonth->isGt($payoutMonth)) {
            throw new CalculateException('The employee is not working yet.');
        }

        $monthDiff = $employmentMonth->diffAbs($payoutMonth);
        $yearsDiff = floor($monthDiff / 12);

        if ($yearsDiff > self::MAX_YEARS_BONUS) {
            $yearsDiff = self::MAX_YEARS_BONUS;
        }

        $bonusPerYear = $employee->getSalaryBonus()->toMoney();

        return $bonusPerYear->mul((string) $yearsDiff);
    }
}
