<?php

namespace MyExpenses\Infrastructure\ReadModel\MySQL\View;

use MyExpenses\Domain\ExpenseList\ExpenseListId;
use MyExpenses\Infrastructure\ReadModel\ExpenseListOverviewView as ExpenseListOverviewViewIface;

class ExpenseListOverviewView implements ExpenseListOverviewViewIface
{
    private $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function expenseListOverviewOfId(ExpenseListId $anExpenseListId): array
    {
        $sql = <<<'SQL'
          SELECT * 
          FROM expense_list_overviews
          WHERE expense_list_id = :expense_list_id
SQL;

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['expense_list_id' => $anExpenseListId->toString()]);
        $stmt->bindColumn('overview', $overview);
        $stmt->fetch(\PDO::FETCH_BOUND);

        return json_decode($overview, true);
    }
}
