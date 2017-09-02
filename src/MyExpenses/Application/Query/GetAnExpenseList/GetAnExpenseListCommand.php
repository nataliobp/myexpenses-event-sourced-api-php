<?php

namespace MyExpenses\Application\Query\GetAnExpenseList;

use MyExpenses\Application\Command;

class GetAnExpenseListCommand implements Command
{
    private $expenseListId;

    public function __construct($anExpenseListId)
    {
        $this->expenseListId = $anExpenseListId;
    }

    public function expenseListId()
    {
        return $this->expenseListId;
    }
}
