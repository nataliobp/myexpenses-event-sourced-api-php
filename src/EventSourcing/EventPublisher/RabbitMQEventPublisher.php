<?php

namespace EventSourcing\EventPublisher;

use EventSourcing\Event\DomainEvent;
use EventSourcing\Event\EventSerializer;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQEventPublisher implements EventPublisher
{
    private $serializer;
    private $queueName;
    private $connection;

    public function __construct(
        AMQPStreamConnection $connection,
        string $queueName,
        EventSerializer $serializer
    ) {
        $this->serializer = $serializer;
        $this->queueName = $queueName;
        $this->connection = $connection;
    }

    public function publish(DomainEvent $event): void
    {
        $channel = $this->connection->channel();
        $channel->queue_declare($this->queueName, false, false, false, false);

        $msg = new AMQPMessage($this->serializer->serialize($event));
        $channel->basic_publish($msg, '', $this->queueName);

        $channel->close();
        $this->connection->close();
    }
}
