<?php

namespace EventSourcing\Event;

class DummyEventSerializer implements EventSerializer
{
    public function serialize(DomainEvent $anEvent): string
    {
        return json_encode([
            'event_name' => get_class($anEvent),
            'event_body' => $anEvent->toArray(),
        ]);
    }

    public function deserialize(string $anEventPayload): DomainEvent
    {
        $anEventPayload = json_decode($anEventPayload, true);
        $eventName = $anEventPayload['event_name'];

        return $eventName::fromArray($anEventPayload['event_body']);
    }
}
