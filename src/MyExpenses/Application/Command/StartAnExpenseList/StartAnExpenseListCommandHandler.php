<?php

namespace MyExpenses\Application\Command\StartAnExpenseList;

use MyExpenses\Application\Command;
use MyExpenses\Application\CommandHandler;
use MyExpenses\Domain\ExpenseList\ExpenseList;
use MyExpenses\Domain\ExpenseList\ExpenseListRepository;

class StartAnExpenseListCommandHandler implements CommandHandler
{
    private $expenseListRepository;

    public function __construct(ExpenseListRepository $expenseListRepository)
    {
        $this->expenseListRepository = $expenseListRepository;
    }

    /**
     * @param Command|StartAnExpenseListCommand $command
     *
     * @return string
     */
    public function handle(Command $command)
    {
        $anExpenseList = ExpenseList::named($command->name());
        $this->expenseListRepository->addAnExpenseList($anExpenseList);

        return $anExpenseList->id()->toString();
    }
}
