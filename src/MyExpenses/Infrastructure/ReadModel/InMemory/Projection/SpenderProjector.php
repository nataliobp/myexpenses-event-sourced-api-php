<?php

namespace MyExpenses\Infrastructure\ReadModel\InMemory\Projection;

use EventSourcing\Projection\InMemoryProjector;
use MyExpenses\Domain\Spender\SpenderWasRegistered;

class SpenderProjector extends InMemoryProjector
{
    const SPENDER_VIEW = 'spender_view';

    protected function whenSpenderWasRegistered(SpenderWasRegistered $anEvent): void
    {
        $this->views(self::SPENDER_VIEW)->append([
            'spender_id' => $anEvent->spenderId(),
            'name' => $anEvent->name(),
            'occurred_on' => $anEvent->occurredOn()->getTimestamp(),
        ]);
    }
}
