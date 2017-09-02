<?php

namespace EventSourcing\EventPublisher;

use EventSourcing\Event\DomainEvent;
use EventSourcing\Event\EventSerializer;
use React\Dns\Resolver\Factory;
use React\EventLoop\Factory as LoopFactory;
use React\SocketClient\ConnectionInterface;
use React\SocketClient\DnsConnector;
use React\SocketClient\TcpConnector;

class ReactPHPEventPublisher implements EventPublisher
{
    private $serializer;
    private $uri;

    public function __construct(string $uri, EventSerializer $serializer)
    {
        $this->serializer = $serializer;
        $this->uri = $uri;
    }

    public function publish(DomainEvent $event): void
    {
        $loop = LoopFactory::create();
        $dnsConnector = new DnsConnector(
            new TcpConnector($loop),
            (new Factory())->createCached('8.8.8.8', $loop)
        );

        $dnsConnector
            ->connect($this->uri)
            ->then(function (ConnectionInterface $conn) use ($event) {
                $conn->write($this->serializer->serialize($event));
                $conn->end();
            });

        $loop->run();
    }
}
