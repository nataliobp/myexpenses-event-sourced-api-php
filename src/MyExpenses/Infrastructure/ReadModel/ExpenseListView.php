<?php

namespace MyExpenses\Infrastructure\ReadModel;

use MyExpenses\Domain\ExpenseList\ExpenseListId;

interface ExpenseListView
{
    public function expenseListOfId(ExpenseListId $expenseListId);
}
