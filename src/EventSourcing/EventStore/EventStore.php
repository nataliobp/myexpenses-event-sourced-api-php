<?php

namespace EventSourcing\EventStore;

use EventSourcing\Event\DomainEvent;
use EventSourcing\Stream\StreamName;

interface EventStore
{
    public function append(DomainEvent $anEvent, StreamName $aStreamName): void;

    public function reconstituteAggregate($anAggregate);
}
