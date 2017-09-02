<?php

namespace MyExpenses\Infrastructure\ReadModel\MySQL\View;

use MyExpenses\Domain\ExpenseList\ExpenseListId;
use MyExpenses\Infrastructure\ReadModel\ExpenseListView as ExpenseListViewIface;

class ExpenseListView implements ExpenseListViewIface
{
    private $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function expenseListOfId(ExpenseListId $anExpenseListId): array
    {
        $sql = <<<'SQL'
          SELECT * 
          FROM expense_lists
          WHERE expense_list_id = :expense_list_id
SQL;

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['expense_list_id' => $anExpenseListId->toString()]);

        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: [];
    }
}
