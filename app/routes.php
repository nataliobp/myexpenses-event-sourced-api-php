<?php

$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->get('/expense/{expenseId}', 'expense_controller:getAnExpense');
$app->get('/expense_list/{expenseListId}', 'expense_controller:getAnExpenseList');
$app->get('/expense_list/{expenseListId}/overview', 'expense_controller:getAnExpenseListOverview');
$app->get('/expense_list/{expenseListId}/categories', 'expense_controller:getCategoriesOfExpenseListOfId');
$app->get('/category/{categoryId}', 'expense_controller:getACategory');
$app->get('/spender/{spenderId}', 'expense_controller:getASpender');

$app->post('/expense_list', 'expense_controller:startAnExpenseList');
$app->post('/expense_list/{expenseListId}/expense', 'expense_controller:addAnExpense');
$app->post('/expense_list/{expenseListId}/category', 'expense_controller:createACategory');
$app->post('/spender', 'expense_controller:registerASpender');
$app->put('/expense/{expenseId}', 'expense_controller:alterAnExpense');
$app->delete('/expense_list/{expenseListId}/expense/{expenseId}', 'expense_controller:removeAnExpense');
