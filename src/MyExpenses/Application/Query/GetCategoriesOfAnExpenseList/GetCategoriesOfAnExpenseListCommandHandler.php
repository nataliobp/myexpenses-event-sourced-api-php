<?php

namespace MyExpenses\Application\Query\GetCategoriesOfAnExpenseList;

use MyExpenses\Application\Command;
use MyExpenses\Application\CommandHandler;
use MyExpenses\Domain\ExpenseList\ExpenseListId;
use MyExpenses\Infrastructure\ReadModel\CategoryView;

class GetCategoriesOfAnExpenseListCommandHandler implements CommandHandler
{
    private $categoryView;

    public function __construct(CategoryView $categoryView)
    {
        $this->categoryView = $categoryView;
    }

    /**
     * @param Command|GetCategoriesOfAnExpenseListCommand $command
     *
     * @return array
     */
    public function handle(Command $command)
    {
        return $this->categoryView->categoriesOfExpenseListOfId(ExpenseListId::ofId($command->expenseListId()));
    }
}
