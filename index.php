<?php
session_start();

require 'vendor/autoload.php';
require 'app/config.php';

// initialize app
$app = new Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
        'db' => $db
    ]

]);
// init containers
$container = $app->getContainer();
require 'app/containers.php';

require 'app/middlewares.php';
// init capsule for eloquent
use Illuminate\Database\Capsule\Manager as Capsule;
$capsule = new Capsule;
$capsule->addConnection($container['settings']['db']);

// Make this Capsule instance available globally via static methods
$capsule->setAsGlobal();
// init eloquent
$capsule->bootEloquent();
$container['db'] = function($container) use ($capsule) {
    return $capsule;
};

// app extensions
// $app->add($container->csrf);
// init app routes
require 'app/routes.php';

// run app
$app->run();