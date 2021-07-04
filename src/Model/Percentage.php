<?php

declare(strict_types=1);

namespace App\Model;

class Percentage
{
    public function __construct(private int $value) {
    }

    public function toDecimal(): string
    {
        return number_format($this->value / 100, 2, '.', '');
    }

    public function getValue()
    {
        return $this->value;
    }
}
