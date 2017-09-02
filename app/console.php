<?php

use MyExpenses\Infrastructure\DeliveryMechanism\Console\RabbitMQEventListenerCommand;
use MyExpenses\Infrastructure\DeliveryMechanism\Console\ReactPHPEventListenerCommand;

$app = require_once __DIR__.'/bootstrap.php';

$commandName = $argv[1];

switch ($commandName) {
    case 'rabbit':
        (new RabbitMQEventListenerCommand($app))->execute();
        break;
    case 'react_php_event_listener':
        (new ReactPHPEventListenerCommand($app))->execute();
        break;
    default:
        echo 'No command found for '.$commandName.PHP_EOL;

}
