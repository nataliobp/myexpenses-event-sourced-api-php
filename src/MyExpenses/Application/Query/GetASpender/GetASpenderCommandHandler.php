<?php

namespace MyExpenses\Application\Query\GetASpender;

use MyExpenses\Application\Command;
use MyExpenses\Application\CommandHandler;
use MyExpenses\Domain\Spender\SpenderId;
use MyExpenses\Infrastructure\ReadModel\SpenderView;

class GetASpenderCommandHandler implements CommandHandler
{
    private $spenderView;

    public function __construct(SpenderView $spenderView)
    {
        $this->spenderView = $spenderView;
    }

    /**
     * @param GetASpenderCommand|Command $command
     *
     * @return array
     */
    public function handle(Command $command)
    {
        return $this->spenderView->spenderOfId(SpenderId::ofId($command->spenderId()));
    }
}
