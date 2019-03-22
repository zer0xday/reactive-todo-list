<?php
namespace App\Controllers;
use \Slim\Views\Twig as View;

class Controller {
    protected $container;

    public function __construct($container) {
        $this->container = $container;
    }

    // if property exist in container then return this property
    // this prevents us to assigning every each container property
    // in class costructor
    public function __get($property) {
        if($this->container->{$property}) {
            return $this->container->{$property};
        }
    }
}