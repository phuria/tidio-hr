<?php

declare(strict_types=1);

namespace App\Service\BonusCalculator;

use App\Model\Employee;
use App\Model\Money;
use App\Model\Month;

class CompositeCalculator implements Calculator
{
    public function __construct(private iterable $calculators)
    {
    }

    public function isSupported(Employee $employee): bool
    {
        /** @var Calculator $calculator */
        foreach ($this->calculators as $calculator) {
            if ($calculator->isSupported($employee)) {
                return true;
            }
        }

        return false;
    }

    public function calculate(Month $payoutMonth, Employee $employee): Money
    {
        /** @var Calculator $calculator */
        foreach ($this->calculators as $calculator) {
            if ($calculator->isSupported($employee)) {
                return $calculator->calculate($payoutMonth, $employee);
            }
        }

        throw new CalculateException('Unsupported employee.');
    }
}
