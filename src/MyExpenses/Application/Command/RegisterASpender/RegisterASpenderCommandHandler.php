<?php

namespace MyExpenses\Application\Command\RegisterASpender;

use MyExpenses\Application\Command;
use MyExpenses\Application\CommandHandler;
use MyExpenses\Domain\Spender\Spender;
use MyExpenses\Domain\Spender\SpenderRepository;

class RegisterASpenderCommandHandler implements CommandHandler
{
    private $spenderRepository;

    public function __construct(SpenderRepository $spenderRepository)
    {
        $this->spenderRepository = $spenderRepository;
    }

    /**
     * @param RegisterASpenderCommand|Command $command
     *
     * @return string
     */
    public function handle(Command $command)
    {
        $aSpender = Spender::named($command->name());
        $this->spenderRepository->addASpender($aSpender);

        return $aSpender->id()->toString();
    }
}
