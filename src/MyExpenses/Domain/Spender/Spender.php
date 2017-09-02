<?php

namespace MyExpenses\Domain\Spender;

use EventSourcing\Aggregate\Aggregate;

class Spender extends Aggregate
{
    private $name;

    public static function named(string $name): self
    {
        $aSpender = new self(SpenderId::create());
        $aSpender->recordThat(SpenderWasRegistered::withData($aSpender->id(), $name));

        return $aSpender;
    }

    public function name(): string
    {
        return $this->name;
    }

    protected function whenSpenderWasRegistered(SpenderWasRegistered $event): void
    {
        $this->name = $event->name();
    }
}
