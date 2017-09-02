<?php

namespace EventSourcing\EventStore;

use EventSourcing\Aggregate\Aggregate;
use EventSourcing\Event\DomainEvent;
use EventSourcing\Event\EventSerializer;
use EventSourcing\Stream\StreamName;
use PHPUnit\Runner\Exception;

class InMemoryEventStore implements EventStore
{
    private $memory = [];
    private $serializer;

    public function __construct(EventSerializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function append(DomainEvent $anEvent, StreamName $aStreamName): void
    {
        $this->memory[$aStreamName->toString()][] = $this->serializer->serialize($anEvent);
    }

    /**
     * @param Aggregate $anAggregate
     *
     * @return Aggregate
     */
    public function reconstituteAggregate($anAggregate)
    {
        if(empty($this->memory[StreamName::fromAggregate($anAggregate)->toString()])){
            throw new Exception(get_class($anAggregate) . ' of id ' . $anAggregate->id()->toString() . ' not exist');
        }

        array_map(
            function ($event) use ($anAggregate) {
                $anAggregate->apply($this->serializer->deserialize($event));
            },
            $this->memory[StreamName::fromAggregate($anAggregate)->toString()]
        );

        return $anAggregate;
    }

    public function popEventOfType($eventName, $aggregateName): ? DomainEvent
    {
        foreach ($this->memory as $streamName => $eventStream) {
            if (StreamName::fromString($streamName)->belongsToAggregate($aggregateName)) {
                foreach ($eventStream as $event) {
                    $event = $this->serializer->deserialize($event);
                    if (get_class($event) === $eventName) {
                        return $event;
                    }
                }
            }
        }

        return null;
    }
}
