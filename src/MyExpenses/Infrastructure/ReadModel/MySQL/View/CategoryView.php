<?php

namespace MyExpenses\Infrastructure\ReadModel\MySQL\View;

use MyExpenses\Domain\Category\CategoryId;
use MyExpenses\Domain\ExpenseList\ExpenseListId;
use MyExpenses\Infrastructure\ReadModel\CategoryView as CategoryViewIface;

class CategoryView implements CategoryViewIface
{
    private $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function categoryOfId(CategoryId $anCategoryId): array
    {
        $sql = <<<'SQL'
          SELECT * 
          FROM categories
          WHERE category_id = :category_id
SQL;

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['category_id' => $anCategoryId->toString()]);

        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: [];
    }

    public function categoriesOfExpenseListOfId(ExpenseListId $anExpenseListId): array
    {
        $sql = <<<'SQL'
          SELECT * 
          FROM categories
          WHERE expense_list_id = :expense_list_id
SQL;

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['expense_list_id' => $anExpenseListId->toString()]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
