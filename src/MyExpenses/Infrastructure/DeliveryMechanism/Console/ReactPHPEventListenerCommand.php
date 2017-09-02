<?php

namespace MyExpenses\Infrastructure\DeliveryMechanism\Console;

class ReactPHPEventListenerCommand extends ContainerAwareCommand
{
    public function execute()
    {
        echo 'Starting React PHP listener...'.PHP_EOL;
        $this->get('react_php_event_listener')->run();
    }
}
