<?php

namespace MyExpenses\Infrastructure\ReadModel\MySQL\Projection;

use EventSourcing\Projection\MySQLProjector;
use MyExpenses\Domain\ExpenseList\ExpenseListWasStarted;

class ExpenseListProjector extends MySQLProjector
{
    protected function whenExpenseListWasStarted(ExpenseListWasStarted $anEvent): void
    {
        $sql = <<<'SQL'
          INSERT INTO expense_lists (expense_list_id, name)
          VALUES (:expense_list_id, :name);
SQL;
        $stmt = $this->connection()->prepare($sql);
        $stmt->execute([
            'expense_list_id' => $anEvent->expenseListId(),
            'name' => $anEvent->name(),
        ]);
    }
}
