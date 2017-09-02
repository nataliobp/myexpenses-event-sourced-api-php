<?php

namespace EventSourcing\EventListener\RabbitMQ;

use EventSourcing\Event\EventSerializer;
use EventSourcing\Projection\Projector;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitMQEventListener
{
    private $eventSerializer;
    private $connection;

    public function __construct(
        AMQPStreamConnection $connection,
        EventSerializer $eventSerializer,
        string $queueName,
        array $projectors
    ) {
        $this->eventSerializer = $eventSerializer;
        $this->connection = $connection;
        $this->projectors = $projectors;
        $this->startListeningToQueue($queueName);
    }

    private function startListeningToQueue(string $queueName): void
    {
        $channel = $this->connection->channel();
        $channel->queue_declare($queueName, false, false, false, false);
        $channel->basic_consume($queueName, '', false, true, false, false, call_user_func([$this, 'run']));

        while (count($channel->callbacks)) {
            $channel->wait();
        }
    }

    public function run(): callable
    {
        return function ($msg) {
            echo '...received: '.$msg->body.PHP_EOL;

            $aDomainEvent = $this->eventSerializer->deserialize($msg->body);
            array_map(
                function (Projector $projector) use ($aDomainEvent) {
                    $projector->project($aDomainEvent);
                },
                $this->projectors[$aDomainEvent->eventName()]
            );
        };
    }
}
