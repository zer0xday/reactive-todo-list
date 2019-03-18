<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';
require 'config.php';

$app = new Slim\App(['settings' => $config]);
$container = $app->getContainer();

// set twig to view renderer engine
$container['view'] = function($container) {
    $view = new \Slim\Views\Twig('views');

    $router = $container->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new Slim\Views\TwigExtension($router, $uri));

    return $view;
};

$app->get('/', function(Request $req, Response $res) {
    return $this->view->render($res, 'pages/home.html');
});

//-------------- Login ------------------
$app->post('/login', function (Request $request, Response $response, $args) use ($app) {
    $json = $request->getParams();
    return $response->withJson($json);
});

//-------------- SignUP ------------------
$app->get('/signup', function(Request $req, Response $res, $args) {
    return $this->view->render($res, 'pages/signup.html');
});

$app->post('/signup', function (Request $request, Response $response, $args) use ($app) {
    $data = $request->getParams();
    return $response->withJson($json);
});

$app->run();