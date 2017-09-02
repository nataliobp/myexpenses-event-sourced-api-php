<?php

use MyExpenses\Infrastructure\ReadModel\MySQL\Projection\CategoryProjector;
use MyExpenses\Infrastructure\ReadModel\MySQL\Projection\ExpenseListOverviewProjector;
use MyExpenses\Infrastructure\ReadModel\MySQL\Projection\ExpenseListProjector;
use MyExpenses\Infrastructure\ReadModel\MySQL\Projection\ExpenseProjector;
use MyExpenses\Infrastructure\ReadModel\MySQL\Projection\SpenderProjector;
use MyExpenses\Infrastructure\ReadModel\MySQL\View\CategoryView;
use MyExpenses\Infrastructure\ReadModel\MySQL\View\ExpenseListOverviewView;
use MyExpenses\Infrastructure\ReadModel\MySQL\View\ExpenseListView;
use MyExpenses\Infrastructure\ReadModel\MySQL\View\ExpenseView;
use MyExpenses\Infrastructure\ReadModel\MySQL\View\SpenderView;

$app['expense_projector'] = function ($app) {
    return new ExpenseProjector($app['pdo'], $app['event_serializer']);
};

$app['spender_projector'] = function ($app) {
    return new SpenderProjector($app['pdo'], $app['event_serializer']);
};

$app['category_projector'] = function ($app) {
    return new CategoryProjector($app['pdo'], $app['event_serializer']);
};

$app['expense_list_projector'] = function ($app) {
    return new ExpenseListProjector($app['pdo'], $app['event_serializer']);
};

$app['expense_list_overview_projector'] = function ($app) {
    return new ExpenseListOverviewProjector(
        $app['pdo'],
        $app['event_serializer'],
        $app['not_publishing_spender_repository'],
        $app['not_publishing_category_repository'],
        $app['not_publishing_expense_repository']
    );
};

$app['expense_view'] = function ($app) {
    return new ExpenseView($app['pdo']);
};

$app['expense_list_overview_view'] = function ($app) {
    return new ExpenseListOverviewView($app['pdo']);
};

$app['expense_list_view'] = function ($app) {
    return new ExpenseListView($app['pdo']);
};

$app['spender_view'] = function ($app) {
    return new SpenderView($app['pdo']);
};

$app['category_view'] = function ($app) {
    return new CategoryView($app['pdo']);
};
