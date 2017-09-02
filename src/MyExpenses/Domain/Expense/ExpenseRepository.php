<?php

namespace MyExpenses\Domain\Expense;

interface ExpenseRepository
{
    public function addAnExpense(Expense $anExpense): void;
    public function expenseOfId(ExpenseId $anExpenseId): Expense;
    public function removeAnExpense(Expense $anExpense): void;
}
