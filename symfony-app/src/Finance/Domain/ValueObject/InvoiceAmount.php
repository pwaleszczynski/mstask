<?php

declare(strict_types=1);

namespace App\Finance\Domain\ValueObject;

final class InvoiceAmount
{
    private float $amount;

    public function __construct(
        float $amount,
    ) {
        if ($amount <= 0 ) {
            throw new \DomainException('Invalid invoice amount');
        }

        $this->amount = $amount;
    }

    public static function from(float $amount): self
    {
        return new self($amount);
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
