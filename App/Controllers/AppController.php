<?php
namespace App\Controllers;
use App\Models\User;

use \Slim\Views\Twig as View;

class AppController extends Controller {
    public function index($req, $res) {
        return $this->view->render($res, 'pages/home.html');
    }

    public function main($req, $res) {
        return $this->view->render($res, 'pages/main.html');
    }

    public function signUp($req, $res) {
        return $this->view->render($res, 'pages/signup.html');
    }
};
