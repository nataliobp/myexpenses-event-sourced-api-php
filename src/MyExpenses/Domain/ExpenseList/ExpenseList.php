<?php

namespace MyExpenses\Domain\ExpenseList;

use EventSourcing\Aggregate\Aggregate;
use MyExpenses\Domain\Category\Category;
use MyExpenses\Domain\Expense\Expense;
use MyExpenses\Domain\Money\Money;
use MyExpenses\Domain\Spender\Spender;

class ExpenseList extends Aggregate
{
    private $name;

    public static function named(string $name): self
    {
        $anExpenseList = new self(ExpenseListId::create());
        $anExpenseList->recordThat(ExpenseListWasStarted::withData($anExpenseList->id(), $name));

        return $anExpenseList;
    }

    public function createACategory(string $name): Category
    {
        return Category::createWithData($name, $this);
    }

    public function removeAnExpense(Expense $anExpense): void
    {
        $anExpense->remove();
    }

    protected function whenExpenseListWasStarted(ExpenseListWasStarted $event): void
    {
        $this->name = $event->name();
    }

    public function addAnExpense(Money $money, Category $aCategory, Spender $aSpender, string $aDescription)
    {
        return Expense::createWithData(
            $money,
            $aCategory,
            $aSpender,
            $aDescription,
            $this
        );
    }
}
