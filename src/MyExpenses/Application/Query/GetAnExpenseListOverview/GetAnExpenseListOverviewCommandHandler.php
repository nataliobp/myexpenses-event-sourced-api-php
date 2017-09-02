<?php

namespace MyExpenses\Application\Query\GetAnExpenseListOverview;

use MyExpenses\Application\Command;
use MyExpenses\Application\CommandHandler;
use MyExpenses\Domain\ExpenseList\ExpenseListId;
use MyExpenses\Infrastructure\ReadModel\ExpenseListOverviewView;

class GetAnExpenseListOverviewCommandHandler implements CommandHandler
{
    private $expenseListOverviewView;

    public function __construct(ExpenseListOverviewView $expenseListOverviewView)
    {
        $this->expenseListOverviewView = $expenseListOverviewView;
    }

    /**
     * @param GetAnExpenseListOverviewCommand|Command $command
     *
     * @return array
     */
    public function handle(Command $command)
    {
        return $this->expenseListOverviewView->expenseListOverviewOfId(ExpenseListId::ofId($command->expenseListId()));
    }
}
