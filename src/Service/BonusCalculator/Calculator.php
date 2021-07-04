<?php

declare(strict_types=1);

namespace App\Service\BonusCalculator;

use App\Model\Employee;
use App\Model\Money;
use App\Model\Month;

interface Calculator
{
    public function isSupported(Employee $employee): bool;

    /**
     * @throws CalculateException
     */
    public function calculate(Month $payoutMonth, Employee $employee): Money;
}
