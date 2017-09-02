<?php

namespace MyExpenses\Domain\ExpenseList;

interface ExpenseListRepository
{
    public function expenseListOfId(ExpenseListId $aExpenseListId): ExpenseList;
    public function addAnExpenseList(ExpenseList $anExpenseList): void;
}
