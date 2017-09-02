<?php

namespace MyExpenses\Application\Command\AlterAnExpense;

use MyExpenses\Application\Command;

class AlterAnExpenseCommand implements Command
{
    private $amount;
    private $description;
    private $categoryId;
    private $expenseId;

    public function __construct(
        string $expenseId,
        int $amount,
        string $description,
        string $categoryId
    ) {
        $this->amount = $amount;
        $this->description = $description;
        $this->categoryId = $categoryId;
        $this->expenseId = $expenseId;
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

    public function expenseId(): string
    {
        return $this->expenseId;
    }
}
