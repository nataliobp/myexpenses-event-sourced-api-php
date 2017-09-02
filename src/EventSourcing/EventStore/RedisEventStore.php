<?php

namespace EventSourcing\EventStore;

use EventSourcing\Aggregate\Aggregate;
use EventSourcing\Event\DomainEvent;
use EventSourcing\Event\EventSerializer;
use EventSourcing\Stream\StreamName;
use Predis\Client;

class RedisEventStore implements EventStore
{
    private $redisClient;
    private $serializer;

    public function __construct(Client $redisClient, EventSerializer $serializer)
    {
        $this->redisClient = $redisClient;
        $this->serializer = $serializer;
    }

    public function append(DomainEvent $anEvent, StreamName $aStreamName): void
    {
        $this->redisClient->rpush($aStreamName, [$this->serializer->serialize($anEvent)]);
    }

    /**
     * @param Aggregate $anAggregate
     *
     * @return Aggregate
     */
    public function reconstituteAggregate($anAggregate)
    {
        array_map(
            function (string $anEventPayload) use ($anAggregate) {
                $anEvent = $this->serializer->deserialize($anEventPayload);
                $anAggregate->apply($anEvent);
            },
            $this->redisClient->lrange(StreamName::fromAggregate($anAggregate), 0, -1)
        );

        return $anAggregate;
    }
}
