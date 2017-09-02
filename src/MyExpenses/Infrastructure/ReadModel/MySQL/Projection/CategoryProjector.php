<?php

namespace MyExpenses\Infrastructure\ReadModel\MySQL\Projection;

use EventSourcing\Projection\MySQLProjector;
use MyExpenses\Domain\Category\CategoryWasCreated;

class CategoryProjector extends MySQLProjector
{
    protected function whenCategoryWasCreated(CategoryWasCreated $anEvent): void
    {
        $sql = <<<'SQL'
          INSERT INTO categories (category_id, name, expense_list_id)
          VALUES (:category_id, :name, :expense_list_id);
SQL;
        $stmt = $this->connection()->prepare($sql);
        $stmt->execute([
            'category_id' => $anEvent->categoryId(),
            'name' => $anEvent->name(),
            'expense_list_id' => $anEvent->expenseListId(),
        ]);
    }
}
