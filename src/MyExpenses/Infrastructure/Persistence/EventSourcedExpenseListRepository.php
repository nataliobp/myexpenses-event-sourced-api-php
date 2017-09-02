<?php

namespace MyExpenses\Infrastructure\Persistence;

use MyExpenses\Domain\ExpenseList\ExpenseList;
use MyExpenses\Domain\ExpenseList\ExpenseListId;
use MyExpenses\Domain\ExpenseList\ExpenseListRepository;
use EventSourcing\Repository\EventSourcedRepository;

class EventSourcedExpenseListRepository extends EventSourcedRepository implements ExpenseListRepository
{
    public function addAnExpenseList(ExpenseList $anExpenseList): void
    {
        $this->appendAndPublishRecordedEvents($anExpenseList);
    }

    public function expenseListOfId(ExpenseListId $anExpenseListId): ExpenseList
    {
        $anExpenseList = new ExpenseList($anExpenseListId);

        return $this->eventStore->reconstituteAggregate($anExpenseList);
    }
}
