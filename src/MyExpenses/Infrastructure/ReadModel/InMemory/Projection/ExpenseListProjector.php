<?php

namespace MyExpenses\Infrastructure\ReadModel\InMemory\Projection;

use EventSourcing\Projection\InMemoryProjector;
use MyExpenses\Domain\ExpenseList\ExpenseListWasStarted;

class ExpenseListProjector extends InMemoryProjector
{
    const EXPENSE_LIST_VIEW = 'expense_list_view';

    protected function whenExpenseListWasStarted(ExpenseListWasStarted $anEvent): void
    {
        $this->views(self::EXPENSE_LIST_VIEW)->append([
            'expense_list_id' => $anEvent->expenseListId(),
            'name' => $anEvent->name(),
            'occurred_on' => $anEvent->occurredOn()->getTimestamp(),
        ]);
    }
}
