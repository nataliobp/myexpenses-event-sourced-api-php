<?php

namespace MyExpenses\Application\Command\StartAnExpenseList;

use MyExpenses\Application\Command;

class StartAnExpenseListCommand implements Command
{
    private $name;

    public function __construct(string $aName)
    {
        $this->name = $aName;
    }

    public function name(): string
    {
        return $this->name;
    }
}
