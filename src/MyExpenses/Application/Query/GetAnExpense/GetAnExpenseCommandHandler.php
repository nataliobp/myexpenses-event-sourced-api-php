<?php

namespace MyExpenses\Application\Query\GetAnExpense;

use MyExpenses\Application\Command;
use MyExpenses\Application\CommandHandler;
use MyExpenses\Domain\Expense\ExpenseId;
use MyExpenses\Infrastructure\ReadModel\ExpenseView;

class GetAnExpenseCommandHandler implements CommandHandler
{
    private $expenseView;

    public function __construct(ExpenseView $expenseView)
    {
        $this->expenseView = $expenseView;
    }

    /**
     * @param GetAnExpenseCommand|Command $command
     *
     * @return array
     */
    public function handle(Command $command)
    {
        return $this->expenseView->expenseOfId(ExpenseId::ofId($command->expenseId()));
    }
}
