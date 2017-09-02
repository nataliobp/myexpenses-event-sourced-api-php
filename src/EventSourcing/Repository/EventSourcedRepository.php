<?php

namespace EventSourcing\Repository;

use EventSourcing\Aggregate\Aggregate;
use EventSourcing\EventPublisher\EventPublisher;
use EventSourcing\EventStore\EventStore;
use EventSourcing\Stream\StreamName;

class EventSourcedRepository
{
    protected $eventStore;
    private $publisher;

    public function __construct(EventStore $eventStore, EventPublisher $publisher)
    {
        $this->eventStore = $eventStore;
        $this->publisher = $publisher;
    }

    public function appendAndPublishRecordedEvents(Aggregate $anAggregate): void
    {
        $streamName = StreamName::fromAggregate($anAggregate);

        while (!$anAggregate->recordedEvents()->isEmpty()) {
            $event = $anAggregate->recordedEvents()->dequeue();
            $this->eventStore->append($event, $streamName);
            $this->publisher->publish($event);
        }
    }
}
