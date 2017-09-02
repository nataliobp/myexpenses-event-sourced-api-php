<?php

namespace MyExpenses\Domain\Expense;

use EventSourcing\Aggregate\Aggregate;
use MyExpenses\Domain\Category\Category;
use MyExpenses\Domain\Category\CategoryId;
use MyExpenses\Domain\ExpenseList\ExpenseList;
use MyExpenses\Domain\ExpenseList\ExpenseListId;
use MyExpenses\Domain\Money\Money;
use MyExpenses\Domain\Spender\Spender;
use MyExpenses\Domain\Spender\SpenderId;

class Expense extends Aggregate
{
    const STATUS_ACTIVE = 1;
    const STATUS_REMOVED = 0;

    private $amount;
    private $categoryId;
    private $spenderId;
    private $expenseListId;
    private $description;
    private $status;

    public static function createWithData(
        Money $money,
        Category $aCategory,
        Spender $aSpender,
        string $aDescription,
        ExpenseList $aExpenseList
    ): self {
        $anExpense = new self(ExpenseId::create());

        $anExpense->recordThat(
            ExpenseWasAdded::withData(
                $anExpense->id(),
                $money->amount(),
                $aCategory->id(),
                $aSpender->id(),
                $aDescription,
                $aExpenseList->id()
            )
        );

        return $anExpense;
    }

    public function alter(
        Money $money,
        Category $aCategory,
        string $aDescription
    ): void {
        $this->recordThat(
            ExpenseWasAltered::withData(
                $this->id(),
                $money->amount(),
                $aCategory->id(),
                $aDescription
            )
        );
    }

    public function remove(): void
    {
        $this->recordThat(ExpenseWasRemoved::ofId($this->id()));
    }

    protected function whenExpenseWasAdded(ExpenseWasAdded $anEvent): void
    {
        $this->amount = Money::fromAmount($anEvent->amount());
        $this->categoryId = CategoryId::ofId($anEvent->categoryId());
        $this->spenderId = SpenderId::ofId($anEvent->spenderId());
        $this->expenseListId = ExpenseListId::ofId($anEvent->expenselistId());
        $this->description = $anEvent->description();
        $this->status = self::STATUS_ACTIVE;
    }

    protected function whenExpenseWasAltered(ExpenseWasAltered $anEvent): void
    {
        $this->amount = Money::fromAmount($anEvent->amount());
        $this->categoryId = CategoryId::ofId($anEvent->categoryId());
        $this->description = $anEvent->description();
    }

    protected function whenExpenseWasRemoved(ExpenseWasRemoved $anEvent): void
    {
        $this->status = self::STATUS_REMOVED;
    }

    public function expenseListId(): ExpenseListId
    {
        return $this->expenseListId;
    }

    public function spenderId(): SpenderId
    {
        return $this->spenderId;
    }
}
