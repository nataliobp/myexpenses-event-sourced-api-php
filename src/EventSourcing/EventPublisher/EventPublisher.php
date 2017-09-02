<?php

namespace EventSourcing\EventPublisher;

use EventSourcing\Event\DomainEvent;

interface EventPublisher
{
    public function publish(DomainEvent $event): void;
}
