<?php
// set twig to view renderer engine
$container['view'] = function($container) {
    $view = new \Slim\Views\Twig('views');

    $router = $container->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new Slim\Views\TwigExtension($router, $uri));

    return $view;
};
// Register flash
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};

$container['validator'] = function() {
    return new App\Validation\Validator;
};

// Define controllers here
$container['AppController'] = function($container) {
    return new App\Controllers\AppController($container);
};

