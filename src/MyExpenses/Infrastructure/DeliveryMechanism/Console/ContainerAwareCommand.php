<?php

namespace MyExpenses\Infrastructure\DeliveryMechanism\Console;

use Silex\Application;

abstract class ContainerAwareCommand
{
    private $container;

    public function __construct(Application $container)
    {
        $this->container = $container;
    }

    public function get(string $serviceId)
    {
        return $this->container[$serviceId];
    }

    abstract public function execute();
}
