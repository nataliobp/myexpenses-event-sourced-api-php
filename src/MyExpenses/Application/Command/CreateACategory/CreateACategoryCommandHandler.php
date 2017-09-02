<?php

namespace MyExpenses\Application\Command\CreateACategory;

use MyExpenses\Application\Command;
use MyExpenses\Application\CommandHandler;
use MyExpenses\Domain\Category\CategoryRepository;
use MyExpenses\Domain\ExpenseList\ExpenseListId;
use MyExpenses\Domain\ExpenseList\ExpenseListRepository;

class CreateACategoryCommandHandler implements CommandHandler
{
    private $expenseListRepository;
    private $categoryRepository;

    public function __construct(
        ExpenseListRepository $expenseListRepository,
        CategoryRepository $categoryRepository
    ) {
        $this->expenseListRepository = $expenseListRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param Command|CreateACategoryCommand $command
     *
     * @return string
     */
    public function handle(Command $command)
    {
        $anExpenseList = $this->expenseListRepository->expenseListOfId(ExpenseListId::ofId($command->expenseListId()));
        $aCategory = $anExpenseList->createACategory($command->name());
        $this->categoryRepository->addACategory($aCategory);

        return $aCategory->id()->toString();
    }
}
