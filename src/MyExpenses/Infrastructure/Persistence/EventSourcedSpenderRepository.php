<?php

namespace MyExpenses\Infrastructure\Persistence;

use MyExpenses\Domain\Spender\Spender;
use MyExpenses\Domain\Spender\SpenderId;
use MyExpenses\Domain\Spender\SpenderRepository;
use EventSourcing\Repository\EventSourcedRepository;

class EventSourcedSpenderRepository extends EventSourcedRepository implements SpenderRepository
{
    public function addASpender(Spender $aSpender): void
    {
        $this->appendAndPublishRecordedEvents($aSpender);
    }

    public function spenderOfId(SpenderId $aSpenderId): Spender
    {
        $aSpender = new Spender($aSpenderId);

        return $this->eventStore->reconstituteAggregate($aSpender);
    }
}
