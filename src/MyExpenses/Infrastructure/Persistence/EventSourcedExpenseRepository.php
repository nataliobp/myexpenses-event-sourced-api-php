<?php

namespace MyExpenses\Infrastructure\Persistence;

use MyExpenses\Domain\Expense\Expense;
use MyExpenses\Domain\Expense\ExpenseId;
use MyExpenses\Domain\Expense\ExpenseRepository;
use EventSourcing\Repository\EventSourcedRepository;

class EventSourcedExpenseRepository extends EventSourcedRepository implements ExpenseRepository
{
    public function addAnExpense(Expense $anExpense): void
    {
        $this->appendAndPublishRecordedEvents($anExpense);
    }

    public function expenseOfId(ExpenseId $anExpenseId): Expense
    {
        $anExpense = new Expense($anExpenseId);

        return $this->eventStore->reconstituteAggregate($anExpense);
    }

    public function removeAnExpense(Expense $anExpense): void
    {
        $this->appendAndPublishRecordedEvents($anExpense);
    }
}
