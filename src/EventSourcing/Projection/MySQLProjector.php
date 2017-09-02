<?php

namespace EventSourcing\Projection;

use EventSourcing\Event\DomainEvent;
use EventSourcing\Event\EventSerializer;

class MySQLProjector implements Projector
{
    private $serializer;
    private $connection;

    public function __construct(\PDO $connection, EventSerializer $serializer)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
    }

    public function project(DomainEvent $anEvent): void
    {
        $methodName = 'when'.$anEvent->eventName();
        $this->$methodName($anEvent);
    }

    public function connection(): \PDO
    {
        return $this->connection;
    }
}
