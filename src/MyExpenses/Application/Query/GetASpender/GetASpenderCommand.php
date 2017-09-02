<?php

namespace MyExpenses\Application\Query\GetASpender;

use MyExpenses\Application\Command;

class GetASpenderCommand implements Command
{
    private $spenderId;

    public function __construct($aSpenderId)
    {
        $this->spenderId = $aSpenderId;
    }

    public function spenderId()
    {
        return $this->spenderId;
    }
}
