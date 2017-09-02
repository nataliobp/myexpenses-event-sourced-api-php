<?php

namespace MyExpenses\Application\Command\AlterAnExpense;

use MyExpenses\Application\Command;
use MyExpenses\Application\CommandHandler;
use MyExpenses\Domain\Category\Category;
use MyExpenses\Domain\Category\CategoryId;
use MyExpenses\Domain\Category\CategoryRepository;
use MyExpenses\Domain\Expense\Expense;
use MyExpenses\Domain\Expense\ExpenseId;
use MyExpenses\Domain\Expense\ExpenseRepository;
use MyExpenses\Domain\Money\Money;

class AlterAnExpenseCommandHandler implements CommandHandler
{
    private $expenseRepository;
    private $categoryRepository;

    public function __construct(
        ExpenseRepository $expenseRepository,
        CategoryRepository $aCategoryRepository
    ) {
        $this->expenseRepository = $expenseRepository;
        $this->categoryRepository = $aCategoryRepository;
    }

    /**
     * @param Command|AlterAnExpenseCommand $command
     *
     * @return string
     */
    public function handle(Command $command)
    {
        $anExpense = $this->expenseOfId($command->expenseId());
        $aCategory = $this->categoryOfId($command->categoryId());

        $anExpense->alter(
            Money::fromAmount($command->amount()),
            $aCategory,
            $command->description()
        );

        $this->expenseRepository->addAnExpense($anExpense);

        return $anExpense->id()->toString();
    }

    private function expenseOfId(string $id): Expense
    {
        return $this->expenseRepository->expenseOfId(ExpenseId::ofId($id));
    }

    private function categoryOfId(string $aCategoryId): Category
    {
        return $this->categoryRepository->categoryOfId(CategoryId::ofId($aCategoryId));
    }
}
