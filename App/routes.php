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