<?php

$app->get('/', 'AppController:index')->setName('home');

//-------------- Login ------------------
$app->post('/login', function (Request $request, Response $response, $args) use ($app) {
    $json = $request->getParams();
    return $response->withJson($json);
});

//-------------- SignUP ------------------
$app->get('/signup', 'AppController:signUp')->setName('signUp');

$app->post('/signup', 'AppController:createAccount')->setName('createAccount');