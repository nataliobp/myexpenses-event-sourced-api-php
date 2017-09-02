<?php

namespace MyExpenses\Application\Command\AddAnExpense;

use MyExpenses\Application\Command;
use MyExpenses\Application\CommandHandler;
use MyExpenses\Domain\Category\Category;
use MyExpenses\Domain\Category\CategoryId;
use MyExpenses\Domain\Category\CategoryRepository;
use MyExpenses\Domain\Expense\ExpenseRepository;
use MyExpenses\Domain\ExpenseList\ExpenseList;
use MyExpenses\Domain\ExpenseList\ExpenseListId;
use MyExpenses\Domain\ExpenseList\ExpenseListRepository;
use MyExpenses\Domain\Money\Money;
use MyExpenses\Domain\Spender\Spender;
use MyExpenses\Domain\Spender\SpenderId;
use MyExpenses\Domain\Spender\SpenderRepository;

class AddAnExpenseCommandHandler implements CommandHandler
{
    private $expenseRepository;
    private $expenseListRepository;
    private $spenderRepository;
    private $categoryRepository;

    public function __construct(
        ExpenseRepository $expenseRepository,
        ExpenseListRepository $aExpenseListRepository,
        SpenderRepository $aSpenderRepository,
        CategoryRepository $aCategoryRepository
    ) {
        $this->expenseRepository = $expenseRepository;
        $this->expenseListRepository = $aExpenseListRepository;
        $this->spenderRepository = $aSpenderRepository;
        $this->categoryRepository = $aCategoryRepository;
    }

    /**
     * @param Command|AddAnExpenseCommand $command
     *
     * @return string
     */
    public function handle(Command $command)
    {
        $aExpenseList = $this->expenseListOfId($command->expenseListId());
        $aSpender = $this->spenderOfId($command->spenderId());
        $aCategory = $this->categoryOfId($command->categoryId());

        $anExpense = $aExpenseList->addAnExpense(
            Money::fromAmount($command->amount()),
            $aCategory,
            $aSpender,
            $command->description()
        );

        $this->expenseRepository->addAnExpense($anExpense);

        return $anExpense->id()->toString();
    }

    private function expenseListOfId(string $id): ExpenseList
    {
        return $this->expenseListRepository->expenseListOfId(ExpenseListId::ofId($id));
    }

    private function spenderOfId($aSpenderId): Spender
    {
        return $this->spenderRepository->spenderOfId(SpenderId::ofId($aSpenderId));
    }

    private function categoryOfId($aCategoryId): Category
    {
        return $this->categoryRepository->categoryOfId(CategoryId::ofId($aCategoryId));
    }
}
