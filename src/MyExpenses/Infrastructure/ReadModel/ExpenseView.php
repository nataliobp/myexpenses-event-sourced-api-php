<?php

namespace MyExpenses\Infrastructure\ReadModel;

use MyExpenses\Domain\Expense\ExpenseId;

interface ExpenseView
{
    public function expenseOfId(ExpenseId $anExpenseId): array;
}
