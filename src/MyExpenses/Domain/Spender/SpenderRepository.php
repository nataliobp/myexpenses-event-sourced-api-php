<?php

namespace MyExpenses\Domain\Spender;

interface SpenderRepository
{
    public function spenderOfId(SpenderId $aSpenderId): Spender;
    public function addASpender(Spender $aSpender): void;
}
