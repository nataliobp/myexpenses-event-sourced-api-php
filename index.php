<?php

header("Access-Control-Allow-Origin: *");
/** @var Silex\Application $app */

$app = require __DIR__.'/app/bootstrap.php';

$app->get('/', function(Symfony\Component\HttpFoundation\Request $request) use ($app){

    return 'Hello!';
});

$app->get('/test', function(Symfony\Component\HttpFoundation\Request $request) use ($app){
    return 'Some tests here!';
});

$app->run();
