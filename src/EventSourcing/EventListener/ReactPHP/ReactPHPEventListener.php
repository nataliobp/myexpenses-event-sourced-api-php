<?php

namespace EventSourcing\EventListener;

use EventSourcing\Event\EventSerializer;
use EventSourcing\Projection\Projector;
use React\EventLoop\Factory;
use React\Socket\ConnectionInterface;
use React\Socket\Server;

class ReactPHPEventListener
{
    private $eventSerializer;
    private $projector;

    public function __construct(
        EventSerializer $eventSerializer,
        string $uri,
        Projector $projector
    ) {
        $this->eventSerializer = $eventSerializer;
        $this->startListeningOnUri($uri);
        $this->projector = $projector;
    }

    private function startListeningOnUri(string $uri): void
    {
        $loop = Factory::create();
        $socket = new Server($uri, $loop);

        $socket->on('connection', function (ConnectionInterface $conn) {
            $conn->on('data', call_user_func([$this, 'run'], $conn));
        });

        $loop->run();
    }

    public function run(ConnectionInterface $conn): callable
    {
        return function ($data) use ($conn) {
            echo '...received: '.$data.PHP_EOL;

            $this->projector->project($this->eventSerializer->deserialize($data));
        };
    }
}
