<?php

//CONTROLLER

use EventSourcing\EventListener\Emitter\EmitterProjectorListener;
use EventSourcing\EventListener\RabbitMQ\RabbitMQEventListener;
use EventSourcing\EventListener\ReactPHPEventListener;
use MyExpenses\Infrastructure\DeliveryMechanism\HTTP\ExpenseController;

$app['expense_controller'] = function () use ($app) {
    return new ExpenseController($app);
};

//EMITTER

$app['emitter_expense_projector_listener'] = function ($app) {
    return new EmitterProjectorListener($app['expense_projector']);
};

$app['emitter_expense_list_overview_projector_listener'] = function ($app) {
    return new EmitterProjectorListener($app['expense_list_overview_projector']);
};

$app['emitter_expense_list_projector_listener'] = function ($app) {
    return new EmitterProjectorListener($app['expense_list_projector']);
};

$app['emitter_spender_projector_listener'] = function ($app) {
    return new EmitterProjectorListener($app['spender_projector']);
};

$app['emitter_category_projector_listener'] = function ($app) {
    return new EmitterProjectorListener($app['category_projector']);
};

//RABBIT MQ

$app['rabbit_mq_projector_listener'] = function ($app) {
    function loadInstancesOfProjectors($app): array
    {
        $projectors = [];
        foreach ($app['config']['rabbit_mq']['event_projectors'] as $event => $projectorServices) {
            foreach ($projectorServices as $projectorService) {
                $projectors[$event][] = $app[$projectorService];
            }
        }

        return $projectors;
    }

    return new RabbitMQEventListener(
        $app['rabbit_mq_connection'],
        $app['event_serializer'],
        $app['config']['rabbit_mq']['queue'],
        loadInstancesOfProjectors($app)
    );
};

//REACT PHP

$app['react_php_projector_listener'] = function ($app) {
    return new ReactPHPEventListener(
        $app['event_serializer'],
        $app['config']['react_php']['uri'],
        $app['expense_projector']
    );
};
