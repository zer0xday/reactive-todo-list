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

// main app content
$app->get('/main', 'AppController:main')
    ->setName('main')
    ->add(new \App\Middleware\AuthMiddleware($container));

$app->get('/account', 'AppController:account')
    ->setName('account')
    ->add(new \App\Middleware\AuthMiddleware($container));

$app->post('/account', 'AuthController:editAccount')
    ->setName('editAccount')
    ->add(new \App\Middleware\AuthMiddleware($container));

//** list API **//
// create list
$app->post('/list/create', 'todoAPI:createList')
    ->add(new \App\Middleware\AuthMiddleware($container));
// edit list title
$app->post('/list/edit/title/{id}', 'todoAPI:editListTitle')
->add(new \App\Middleware\AuthMiddleware($container));
// delete list
$app->post('/list/remove/{id}', 'todoAPI:removeList')
    ->add(new \App\Middleware\AuthMiddleware($container));

// create task
$app->post('/task/create/{list_id}', 'todoAPI:createTask')
    ->add(new \App\Middleware\AuthMiddleware($container));
// edit task status
$app->post('/task/edit/status/{id}', 'todoAPI:editTaskStatus')
    ->add(new \App\Middleware\AuthMiddleware($container));
// remove task
$app->post('/task/remove/{id}', 'todoAPI:removeTask')
    ->add(new \App\Middleware\AuthMiddleware($container));