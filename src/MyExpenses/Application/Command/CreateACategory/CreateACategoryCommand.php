<?php

namespace MyExpenses\Application\Command\CreateACategory;

use MyExpenses\Application\Command;

class CreateACategoryCommand implements Command
{
    private $name;
    private $expenseListId;

    public function __construct(string $aName, string $aExpenseListId)
    {
        $this->name = $aName;
        $this->expenseListId = $aExpenseListId;
    }

    public function name()
    {
        return $this->name;
    }

    public function expenseListId()
    {
        return $this->expenseListId;
    }
}
