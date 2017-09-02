<?php

namespace MyExpenses\Domain\Category;

use EventSourcing\Aggregate\Aggregate;
use MyExpenses\Domain\ExpenseList\ExpenseList;

class Category extends Aggregate
{
    private $name;
    private $expenseListId;

    public static function createWithData(string $name, ExpenseList $expenseList)
    {
        $aCategory = new self(CategoryId::create());
        $aCategory->recordThat(
            CategoryWasCreated::withData(
                $aCategory->id(),
                $name,
                $expenseList->id()
            )
        );

        return $aCategory;
    }

    public function name()
    {
        return $this->name;
    }

    protected function whenCategoryWasCreated(CategoryWasCreated $event): void
    {
        $this->name = $event->name();
        $this->expenseListId = $event->expenseListId();
    }
}
