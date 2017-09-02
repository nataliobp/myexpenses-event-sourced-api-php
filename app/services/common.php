<?php

use EventSourcing\Event\DummyEventSerializer;
use EventSourcing\EventPublisher\DummyEventPublisher;
use EventSourcing\EventPublisher\EmitterEventPublisher;
use EventSourcing\EventPublisher\RabbitMQEventPublisher;
use EventSourcing\EventStore\InMemoryEventStore;
use EventSourcing\EventStore\RedisEventStore;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Predis\Client;
use Symfony\Component\Yaml\Yaml;

$app['config'] = function () {
    return Yaml::parse(file_get_contents(__DIR__.'/../config.yml'));
};

$app['emitter_event_publisher'] = function ($app) {
    $subscriptions = [];

    foreach ($app['config']['listeners'] as $event => $listeners) {
        foreach ($listeners as $listener) {
            $subscriptions[$event][] = $app[$listener];
        }
    }

    return new EmitterEventPublisher($subscriptions);
};

$app['dummy_event_serializer'] = function () {
    return new DummyEventSerializer();
};

$app['dummy_event_publisher'] = function () {
    return new DummyEventPublisher();
};

$app['rabbit_mq_connection'] = function ($app) {
    return new AMQPStreamConnection(
        $app['config']['rabbit_mq']['host'],
        $app['config']['rabbit_mq']['port'],
        $app['config']['rabbit_mq']['user'],
        $app['config']['rabbit_mq']['passwd']
    );
};

$app['rabbit_mq_event_publisher'] = function ($app) {
    return new RabbitMQEventPublisher(
        $app['rabbit_mq_connection'],
        $app['config']['rabbit_mq']['queue'],
        $app['event_serializer']
    );
};

$app['pdo'] = function () use ($app) {
    $pdo = new \PDO(
        sprintf('mysql:host=%s;dbname=%s', $app['config']['mysql']['hostname'], $app['config']['mysql']['database']),
        $app['config']['mysql']['username'],
        $app['config']['mysql']['password'],
        [\PDO::ATTR_PERSISTENT => true]
    );

    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    return $pdo;
};

$app['in_memory_event_store'] = function ($app) {
    return new InMemoryEventStore($app['event_serializer']);
};

$app['redis_client'] = function ($app) {
    return new Client($app['config']['redis']['connection']);
};

$app['redis_event_store'] = function ($app) {
    return new RedisEventStore(
        $app['redis_client'],
        $app['event_serializer']
    );
};

$app['event_store'] = function ($app) {
    return $app['redis_event_store'];
};

$app['event_serializer'] = function ($app) {
    return $app['dummy_event_serializer'];
};

$app['event_publisher'] = function ($app) {
    return $app['rabbit_mq_event_publisher'];
};
