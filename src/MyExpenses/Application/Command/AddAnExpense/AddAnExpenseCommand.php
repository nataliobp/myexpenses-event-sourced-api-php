<?php

namespace MyExpenses\Application\Command\AddAnExpense;

use MyExpenses\Application\Command;

class AddAnExpenseCommand implements Command
{
    private $amount;
    private $description;
    private $categoryId;
    private $spenderId;
    private $expenseListId;

    public function __construct(
        int $amount,
        string $aDescription,
        string $aCategoryId,
        string $aSpenderId,
        string $aExpenseListId
    ) {
        $this->amount = $amount;
        $this->description = $aDescription;
        $this->categoryId = $aCategoryId;
        $this->spenderId = $aSpenderId;
        $this->expenseListId = $aExpenseListId;
    }

    public function amount(): int
    {
        return $this->amount;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function categoryId(): string
    {
        return $this->categoryId;
    }

    public function spenderId(): string
    {
        return $this->spenderId;
    }

    public function expenseListId(): string
    {
        return $this->expenseListId;
    }
}
