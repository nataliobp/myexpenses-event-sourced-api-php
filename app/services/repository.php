<?php

use MyExpenses\Infrastructure\Persistence\EventSourcedCategoryRepository;
use MyExpenses\Infrastructure\Persistence\EventSourcedExpenseListRepository;
use MyExpenses\Infrastructure\Persistence\EventSourcedExpenseRepository;
use MyExpenses\Infrastructure\Persistence\EventSourcedSpenderRepository;

$app['expense_repository'] = function ($app) {
    return new EventSourcedExpenseRepository(
        $app['event_store'],
        $app['event_publisher']
    );
};

$app['expense_list_repository'] = function ($app) {
    return new EventSourcedExpenseListRepository(
        $app['event_store'],
        $app['event_publisher']
    );
};

$app['spender_repository'] = function ($app) {
    return new EventSourcedSpenderRepository(
        $app['event_store'],
        $app['event_publisher']
    );
};

$app['category_repository'] = function ($app) {
    return new EventSourcedCategoryRepository(
        $app['event_store'],
        $app['event_publisher']
    );
};

$app['not_publishing_spender_repository'] = function ($app) {
    return new EventSourcedSpenderRepository(
        $app['event_store'],
        $app['dummy_event_publisher']
    );
};

$app['not_publishing_category_repository'] = function ($app) {
    return new EventSourcedCategoryRepository(
        $app['event_store'],
        $app['dummy_event_publisher']
    );
};

$app['not_publishing_expense_repository'] = function ($app) {
    return new EventSourcedExpenseRepository(
        $app['event_store'],
        $app['dummy_event_publisher']
    );
};
