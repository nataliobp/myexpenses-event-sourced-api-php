<?php

namespace EventSourcing\Event;

interface EventSerializer
{
    public function serialize(DomainEvent $anEvent): string;

    public function deserialize(string $anEventPayload): DomainEvent;
}
