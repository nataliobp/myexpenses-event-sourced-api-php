<?php

namespace MyExpenses\Application\Command\RegisterASpender;

use MyExpenses\Application\Command;

class RegisterASpenderCommand implements Command
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
