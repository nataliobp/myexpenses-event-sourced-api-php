<?php

namespace EventSourcing\EventPublisher;

use EventSourcing\Event\DomainEvent;
use League\Event\Emitter;

class EmitterEventPublisher implements EventPublisher
{
    private $emitter;

    public function __construct(array $subscriptions)
    {
        $this->emitter = new Emitter();

        foreach ($subscriptions as $event => $listeners) {
            foreach ($listeners as $listener) {
                $this->emitter->addListener($event, $listener);
            }
        }
    }

    public function publish(DomainEvent $event): void
    {
        $this->emitter->emit($event->eventName(), $event);
    }
}
