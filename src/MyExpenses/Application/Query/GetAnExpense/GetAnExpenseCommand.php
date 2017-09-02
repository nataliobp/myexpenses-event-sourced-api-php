<?php

namespace MyExpenses\Application\Query\GetAnExpense;

use MyExpenses\Application\Command;

class GetAnExpenseCommand implements Command
{
    private $expenseId;

    public function __construct(string $anExpenseId)
    {
        $this->expenseId = $anExpenseId;
    }

    public function expenseId(): string
    {
        return $this->expenseId;
    }
}
