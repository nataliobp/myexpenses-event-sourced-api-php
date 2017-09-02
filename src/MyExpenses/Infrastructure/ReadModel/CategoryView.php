<?php

namespace MyExpenses\Infrastructure\ReadModel;

use MyExpenses\Domain\Category\CategoryId;
use MyExpenses\Domain\ExpenseList\ExpenseListId;

interface CategoryView
{
    public function categoryOfId(CategoryId $anCategoryId): array;

    public function categoriesOfExpenseListOfId(ExpenseListId $anExpenseListId): array;
}
