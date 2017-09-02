<?php

namespace EventSourcing\EventPublisher;

use EventSourcing\Event\DomainEvent;

class DummyEventPublisher implements EventPublisher
{
    public function publish(DomainEvent $event): void
    {
    }
}
