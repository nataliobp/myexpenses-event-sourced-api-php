<?php

namespace MyExpenses\Infrastructure\DeliveryMechanism\HTTP;

use League\Tactician\CommandBus;
use Silex\Application;

class RestController
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function commandBus(): CommandBus
    {
        return $this->app['command_bus'];
    }

    public function get($serviceId)
    {
        return $this->app[$serviceId];
    }
}
