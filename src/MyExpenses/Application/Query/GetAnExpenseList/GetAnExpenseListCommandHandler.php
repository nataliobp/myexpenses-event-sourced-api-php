<?php

namespace MyExpenses\Application\Query\GetAnExpenseList;

use MyExpenses\Application\Command;
use MyExpenses\Application\CommandHandler;
use MyExpenses\Domain\ExpenseList\ExpenseListId;
use MyExpenses\Infrastructure\ReadModel\ExpenseListView;

class GetAnExpenseListCommandHandler implements CommandHandler
{
    private $expenseListView;

    public function __construct(ExpenseListView $expenseListView)
    {
        $this->expenseListView = $expenseListView;
    }

    /**
     * @param GetAnExpenseListCommand|Command $command
     *
     * @return array
     */
    public function handle(Command $command)
    {
        return $this->expenseListView->expenseListOfId(ExpenseListId::ofId($command->expenseListId()));
    }
}
