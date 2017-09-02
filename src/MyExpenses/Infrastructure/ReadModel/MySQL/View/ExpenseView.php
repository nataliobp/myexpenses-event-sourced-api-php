<?php

namespace MyExpenses\Infrastructure\ReadModel\MySQL\View;

use MyExpenses\Domain\Expense\ExpenseId;

class ExpenseView implements \MyExpenses\Infrastructure\ReadModel\ExpenseView
{
    private $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function expenseOfId(ExpenseId $anExpenseId): array
    {
        $sql = <<<'SQL'
          SELECT * 
          FROM expenses
          WHERE expense_id = :expense_id
SQL;

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['expense_id' => $anExpenseId->toString()]);

        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: [];
    }
}
