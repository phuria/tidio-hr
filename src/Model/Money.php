<?php

declare(strict_types=1);

namespace App\Model;

use RuntimeException;

class Money
{
    public function __construct(private string $value)
    {
        if (false === is_numeric($this->value)) {
            throw new RuntimeException('Invalid money value.');
        }
    }

    public function toString(): string
    {
        return number_format((float) $this->value, 2) . ' USD';
    }

    public function add(Money $other): Money
    {
        return new self(bcadd($this->value, $other->value, 2));
    }

    public function percent(Percentage $percentage): Money
    {
        return new self(bcmul($this->value, $percentage->toDecimal(), 2));
    }

    public function mul(string $mul): self
    {
        return new self(bcmul($this->value, $mul, 2));
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
