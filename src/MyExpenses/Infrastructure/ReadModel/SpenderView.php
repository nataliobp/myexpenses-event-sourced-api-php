<?php

namespace MyExpenses\Infrastructure\ReadModel;

use MyExpenses\Domain\Spender\SpenderId;

interface SpenderView
{
    public function spenderOfId(SpenderId $anSpenderId): array;
}
