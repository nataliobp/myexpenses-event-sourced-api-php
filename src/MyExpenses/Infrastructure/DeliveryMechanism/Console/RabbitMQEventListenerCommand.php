<?php

namespace MyExpenses\Infrastructure\DeliveryMechanism\Console;

class RabbitMQEventListenerCommand extends ContainerAwareCommand
{
    public function execute()
    {
        echo 'Starting Rabbit MQ listener...'.PHP_EOL;
        $this->get('rabbit_mq_projector_listener')->run();
    }
}
