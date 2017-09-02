<?php

namespace MyExpenses\Domain\Money;

class Money
{
    private $amount;
    private $currency;

    private function __construct(int $amount, string $currency = '€')
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public static function fromAmount(int $amount): self
    {
        return new self($amount);
    }

    public function amount()
    {
        return $this->amount;
    }
}
