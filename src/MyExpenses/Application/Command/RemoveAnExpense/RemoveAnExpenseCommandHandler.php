<?php

namespace MyExpenses\Application\Command\RemoveAnExpense;

use MyExpenses\Application\Command;
use MyExpenses\Application\CommandHandler;
use MyExpenses\Domain\Expense\ExpenseId;
use MyExpenses\Domain\Expense\ExpenseRepository;
use MyExpenses\Domain\ExpenseList\ExpenseListId;
use MyExpenses\Domain\ExpenseList\ExpenseListRepository;

class RemoveAnExpenseCommandHandler implements CommandHandler
{
    private $expenseListRepository;
    private $expenseRepository;

    public function __construct(
        ExpenseListRepository $expenseListRepository,
        ExpenseRepository $expenseRepository
    ) {
        $this->expenseListRepository = $expenseListRepository;
        $this->expenseRepository = $expenseRepository;
    }

    /**
     * @param Command|RemoveAnExpenseCommand $command
     */
    public function handle(Command $command)
    {
        $anExpense = $this->expenseRepository->expenseOfId(ExpenseId::ofId($command->expenseId()));
        $anExpenseList = $this->expenseListRepository->expenseListOfId(ExpenseListId::ofId($command->expenseListId()));
        $anExpenseList->removeAnExpense($anExpense);
        $this->expenseRepository->removeAnExpense($anExpense);
    }
}
