<?php

namespace MyExpenses\Infrastructure\ReadModel\InMemory\Projection;

use EventSourcing\Projection\InMemoryProjector;

class ExpenseListOverviewProjector extends InMemoryProjector
{
    const EXPENSE_LIST_OVERVIEW_VIEW = 'expense_list_overview_view';
}
