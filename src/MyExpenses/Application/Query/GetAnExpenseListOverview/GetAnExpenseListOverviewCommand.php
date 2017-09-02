<?php

namespace MyExpenses\Application\Query\GetAnExpenseListOverview;

use MyExpenses\Application\Command;

class GetAnExpenseListOverviewCommand implements Command
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
