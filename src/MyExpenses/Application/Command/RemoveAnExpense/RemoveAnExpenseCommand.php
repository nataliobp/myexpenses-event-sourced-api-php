<?php

namespace MyExpenses\Application\Command\RemoveAnExpense;

use MyExpenses\Application\Command;

class RemoveAnExpenseCommand implements Command
{
    private $expenseListId;
    private $expenseId;

    public function __construct($expenseListId, $expenseId)
    {
        $this->expenseListId = $expenseListId;
        $this->expenseId = $expenseId;
    }

    public function expenseListId(): string
    {
        return $this->expenseListId;
    }

    public function expenseId(): string
    {
        return $this->expenseId;
    }
}
