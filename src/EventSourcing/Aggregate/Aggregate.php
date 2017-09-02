<?php

namespace EventSourcing\Aggregate;

use EventSourcing\Event\DomainEvent;

abstract class Aggregate
{
    protected $id;
    protected $recordedEvents;

    public function __construct(AggregateId $id)
    {
        $this->id = $id;
        $this->recordedEvents = new \SplQueue();
    }

    protected function recordThat(DomainEvent $event): void
    {
        $this->recordedEvents->enqueue($event);
        $this->apply($event);
    }

    public function apply(DomainEvent $event): void
    {
        $methodName = 'when'.$event->eventName();

        if (!method_exists($this, $methodName)) {
            throw new \Exception(sprintf('Invalid method name %s for class %s', $methodName, get_called_class()));
        }

        $this->$methodName($event);
    }

    public function id(): AggregateId
    {
        return $this->id;
    }

    public function recordedEvents(): \SplQueue
    {
        return $this->recordedEvents;
    }
}
