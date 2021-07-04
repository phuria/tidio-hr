<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\Employee;
use App\Model\Month;
use App\Model\Payout;
use App\Service\BonusCalculator\Calculator;

class PayoutFactory
{
    /**
     * @var Calculator
     */
    private Calculator $calculator;

    public function __construct(Calculator $calculator)
    {
        $this->calculator = $calculator;
    }

    public function create(Month $payoutMonth, Employee $employee): Payout
    {
        return new Payout(
            $employee,
            $payoutMonth,
            $this->calculator->calculate($payoutMonth, $employee)
        );
    }
}
