<?php

use MyExpenses\Application\Command\AddAnExpense\AddAnExpenseCommandHandler;
use MyExpenses\Application\Command\AlterAnExpense\AlterAnExpenseCommand;
use MyExpenses\Application\Command\AlterAnExpense\AlterAnExpenseCommandHandler;
use MyExpenses\Application\Command\CreateACategory\CreateACategoryCommand;
use MyExpenses\Application\Command\CreateACategory\CreateACategoryCommandHandler;
use MyExpenses\Application\Command\RegisterASpender\RegisterASpenderCommand;
use MyExpenses\Application\Command\RegisterASpender\RegisterASpenderCommandHandler;
use MyExpenses\Application\Command\RemoveAnExpense\RemoveAnExpenseCommand;
use MyExpenses\Application\Command\RemoveAnExpense\RemoveAnExpenseCommandHandler;
use MyExpenses\Application\Command\StartAnExpenseList\StartAnExpenseListCommand;
use MyExpenses\Application\Command\StartAnExpenseList\StartAnExpenseListCommandHandler;
use MyExpenses\Application\Query\GetAnExpense\GetAnExpenseCommandHandler;
use MyExpenses\Application\Command\AddAnExpense\AddAnExpenseCommand;
use MyExpenses\Application\Query\GetAnExpense\GetAnExpenseCommand;
use MyExpenses\Application\Query\GetAnExpenseList\GetAnExpenseListCommand;
use MyExpenses\Application\Query\GetAnExpenseList\GetAnExpenseListCommandHandler;
use MyExpenses\Application\Query\GetAnExpenseListOverview\GetAnExpenseListOverviewCommand;
use MyExpenses\Application\Query\GetAnExpenseListOverview\GetAnExpenseListOverviewCommandHandler;
use MyExpenses\Application\Query\GetACategory\GetACategoryCommand;
use MyExpenses\Application\Query\GetACategory\GetACategoryCommandHandler;
use MyExpenses\Application\Query\GetASpender\GetASpenderCommand;
use MyExpenses\Application\Query\GetASpender\GetASpenderCommandHandler;
use MyExpenses\Application\Query\GetCategoriesOfAnExpenseList\GetCategoriesOfAnExpenseListCommand;
use MyExpenses\Application\Query\GetCategoriesOfAnExpenseList\GetCategoriesOfAnExpenseListCommandHandler;

$app['command_bus'] = function () use ($app) {
    return League\Tactician\Setup\QuickStart::create([
        AddAnExpenseCommand::class => $app['add_expense_command_handler'],
        AlterAnExpenseCommand::class => $app['alter_expense_command_handler'],
        StartAnExpenseListCommand::class => $app['start_an_expense_list_command_handler'],
        RegisterASpenderCommand::class => $app['register_a_spender_command_handler'],
        CreateACategoryCommand::class => $app['create_a_category_command_handler'],
        RemoveAnExpenseCommand::class => $app['remove_an_expense_command_handler'],

        GetAnExpenseCommand::class => $app['get_expense_command_handler'],
        GetAnExpenseListOverviewCommand::class => $app['get_expense_view_overview_command_handler'],
        GetACategoryCommand::class => $app['get_category_command_handler'],
        GetASpenderCommand::class => $app['get_spender_command_handler'],
        GetAnExpenseListCommand::class => $app['get_expense_list_command_handler'],
        GetCategoriesOfAnExpenseListCommand::class => $app['get_categories_of_expense_list_command_handler'],
    ]);
};

$app['add_expense_command_handler'] = function ($app) {
    return new AddAnExpenseCommandHandler(
        $app['expense_repository'],
        $app['expense_list_repository'],
        $app['spender_repository'],
        $app['category_repository']
    );
};

$app['alter_expense_command_handler'] = function ($app) {
    return new AlterAnExpenseCommandHandler(
        $app['expense_repository'],
        $app['category_repository']
    );
};

$app['start_an_expense_list_command_handler'] = function ($app) {
    return new StartAnExpenseListCommandHandler(
        $app['expense_list_repository']
    );
};

$app['register_a_spender_command_handler'] = function ($app) {
    return new RegisterASpenderCommandHandler(
        $app['spender_repository']
    );
};

$app['create_a_category_command_handler'] = function ($app) {
    return new CreateACategoryCommandHandler(
        $app['expense_list_repository'],
        $app['category_repository']
    );
};

$app['remove_an_expense_command_handler'] = function ($app) {
    return new RemoveAnExpenseCommandHandler(
        $app['expense_list_repository'],
        $app['expense_repository']
    );
};

$app['get_expense_command_handler'] = function ($app) {
    return new GetAnExpenseCommandHandler(
        $app['expense_view']
    );
};

$app['get_expense_view_overview_command_handler'] = function ($app) {
    return new GetAnExpenseListOverviewCommandHandler(
        $app['expense_list_overview_view']
    );
};

$app['get_spender_command_handler'] = function ($app) {
    return new GetASpenderCommandHandler(
        $app['spender_view']
    );
};

$app['get_category_command_handler'] = function ($app) {
    return new GetACategoryCommandHandler(
        $app['category_view']
    );
};

$app['get_expense_list_command_handler'] = function ($app) {
    return new GetAnExpenseListCommandHandler(
        $app['expense_list_view']
    );
};

$app['get_categories_of_expense_list_command_handler'] = function ($app) {
    return new GetCategoriesOfAnExpenseListCommandHandler(
        $app['category_view']
    );
};
