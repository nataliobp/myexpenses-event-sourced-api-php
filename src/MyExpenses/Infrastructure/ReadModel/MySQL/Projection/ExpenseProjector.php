<?php

namespace MyExpenses\Infrastructure\ReadModel\MySQL\Projection;

use EventSourcing\Projection\MySQLProjector;
use MyExpenses\Domain\Expense\ExpenseWasAdded;
use MyExpenses\Domain\Expense\ExpenseWasAltered;
use MyExpenses\Domain\Expense\ExpenseWasRemoved;

class ExpenseProjector extends MySQLProjector
{
    protected function whenExpenseWasAdded(ExpenseWasAdded $anEvent): void
    {
        $sql = <<<'SQL'
          INSERT INTO expenses(
              expense_id, 
              amount, 
              description, 
              category_id, 
              spender_id, 
              expense_list_id, 
              occurred_on
          ) VALUES(
              :expense_id, 
              :amount, 
              :description, 
              :category_id, 
              :spender_id, 
              :expense_list_id, 
              :occurred_on
          )
SQL;

        $stmt = $this->connection()->prepare($sql);

        $stmt->execute([
            'expense_id' => $anEvent->expenseId(),
            'amount' => $anEvent->amount(),
            'description' => $anEvent->description(),
            'category_id' => $anEvent->categoryId(),
            'spender_id' => $anEvent->spenderId(),
            'expense_list_id' => $anEvent->expenseListId(),
            'occurred_on' => $anEvent->occurredOn()->format('Y-m-d H:i:s'),
        ]);
    }

    protected function whenExpenseWasAltered(ExpenseWasAltered $anEvent): void
    {
        $sql = <<<'SQL'
          UPDATE 
              expenses
          SET 
              amount = :amount,
              description = :description,
              category_id = :category_id
          WHERE
              expense_id = :expense_id
SQL;

        $stmt = $this->connection()->prepare($sql);

        $stmt->execute([
            'expense_id' => $anEvent->expenseId(),
            'amount' => $anEvent->amount(),
            'description' => $anEvent->description(),
            'category_id' => $anEvent->categoryId(),
        ]);
    }

    protected function whenExpenseWasRemoved(ExpenseWasRemoved $anEvent): void
    {
        $sql = <<<'SQL'
          DELETE FROM 
              expenses
          WHERE
              expense_id = :expense_id
SQL;

        $stmt = $this->connection()->prepare($sql);
        $stmt->execute(['expense_id' => $anEvent->expenseId()]);
    }
}
