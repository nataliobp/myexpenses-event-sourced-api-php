<?php

namespace MyExpenses\Infrastructure\ReadModel;

use MyExpenses\Domain\ExpenseList\ExpenseListId;

interface ExpenseListOverviewView
{
    public function expenseListOverviewOfId(ExpenseListId $anExpenseListId): array;
}
