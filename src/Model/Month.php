<?php

declare(strict_types=1);

namespace App\Model;

use DateTimeImmutable;
use DateTimeInterface;
use RuntimeException;

class Month
{
    public static function fromString(string $month): self
    {
        preg_match('/^(\d{4})-(\d{2})/', $month, $matches);

        if (0 === count($matches)) {
            throw new RuntimeException('Invalid month format.');
        }

        return new self((int) $matches[1], (int) $matches[2]);
    }

    public static function fromDateTime(DateTimeInterface $dateTime): self
    {
        return new self(
            (int) $dateTime->format('Y'),
            (int) $dateTime->format('m')
        );
    }

    public function __construct(private int $year, private int $month)
    {
    }

    public function isGt(Month $other): bool
    {
        return $this->diff($other) > 0;
    }

    public function toDateTime(): DateTimeInterface
    {
        return new DateTimeImmutable("{$this->year}-{$this->month}-01 00:00:00");
    }

    public function diff(Month $other): int
    {
        return (($this->year - $other->year) * 12) + ($this->month - $other->month);
    }

    public function diffAbs(Month $other): int
    {
        return abs($this->diff($other));
    }
}
