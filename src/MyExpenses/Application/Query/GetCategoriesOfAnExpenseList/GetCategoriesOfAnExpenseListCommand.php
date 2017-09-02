<?php

namespace MyExpenses\Application\Query\GetCategoriesOfAnExpenseList;

use MyExpenses\Application\Command;

class GetCategoriesOfAnExpenseListCommand implements Command
{
    private $expenseListId;

    public function __construct(string $anExpenseListId)
    {
        $this->expenseListId = $anExpenseListId;
    }

    public function expenseListId(): string
    {
        return $this->expenseListId;
    }
}
