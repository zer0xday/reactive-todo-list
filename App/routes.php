<?php

$app->get('/', 'AppController:index')->setName('home');

//-------------- Login ------------------
$app->post('/login', 'AuthController:login')
    ->setName('login');

$app->post('/logout', 'AuthController:logout')
    ->setName('logout');

//-------------- SignUP ------------------
$app->get('/signup', 'AppController:signUp')
    ->setName('signUp');

$app->post('/signup', 'AuthController:createAccount')
    ->setName('createAccount');

$app->get('/main', 'AppController:main')
    ->setName('main')
    ->add(new \App\Middleware\AuthMiddleware($container));

// list API
$app->post('/list/create', 'todoAPI:createList')
    ->add(new \App\Middleware\AuthMiddleware($container));
$app->post('/list/delete/:id', 'todoAPI:deleteList')
    ->add(new \App\Middleware\AuthMiddleware($container));

$app->post('/task/create', 'todoAPI:taskCreate')
    ->add(new \App\Middleware\AuthMiddleware($container));