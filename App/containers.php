<?php
// set twig to view renderer engine
$container['view'] = function($c) {
    $view = new \Slim\Views\Twig('views');

    $router = $c->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new Slim\Views\TwigExtension($router, $uri));

    return $view;
};
// Register flash
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};

// register CSRF protection
$container['csrf'] = function ($c) {
    return new \Slim\Csrf\Guard;
};

$container['validator'] = function() {
    return new App\Validation\Validator;
};

$container['auth'] = function($c) {
    return new App\Auth\Auth($c);
};

// Define controllers here
$container['AppController'] = function($c) {
    return new App\Controllers\AppController($c);
};
$container['AuthController'] = function($c) {
    return new App\Controllers\AuthController($c);
};

