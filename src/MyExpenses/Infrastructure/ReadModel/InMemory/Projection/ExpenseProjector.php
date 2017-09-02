<?php

namespace MyExpenses\Infrastructure\ReadModel\InMemory\Projection;

use EventSourcing\Projection\InMemoryProjector;
use MyExpenses\Domain\Expense\ExpenseWasAdded;

class ExpenseProjector extends InMemoryProjector
{
    const EXPENSE_VIEW = 'expense_view';

    protected function whenExpenseWasAdded(ExpenseWasAdded $anEvent): void
    {
        $this->views(self::EXPENSE_VIEW)->append([
            'expense_id' => $anEvent->expenseId(),
            'amount' => $anEvent->amount(),
            'description' => $anEvent->description(),
            'category_id' => $anEvent->categoryId(),
            'spender_id' => $anEvent->spenderId(),
            'expense_list_id' => $anEvent->expenseListId(),
            'occurred_on' => $anEvent->occurredOn()->getTimestamp(),
        ]);
    }
}
