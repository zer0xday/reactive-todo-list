<?php
namespace App\Controllers;
use App\Models\User;
use App\Models\TODOList;

use \Slim\Views\Twig as View;

class AppController extends Controller {
    public function index($req, $res) {
        if(!isset($_SESSION['user'])) {
            return $this->view->render($res, 'pages/home.html');
        } else {
            return $this->main($req, $res);
        }
    }

    public function main($req, $res) {
        $lists = TODOList::orderBy('id', 'desc')->get();

        return $this->view->render($res, 'pages/main.html', [
            'lists' => $lists
        ]);
    }

    public function signUp($req, $res) {
        return $this->view->render($res, 'pages/signup.html');
    }

    public function account($req, $res) {
        $user = User::find($_SESSION['user']);

        return $this->view->render($res, 'pages/account.html', ['user' => $user]);
    }
};
