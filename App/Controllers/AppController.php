<?php
namespace App\Controllers;
use App\Models\User;
use App\Models\TODOList;

use \Slim\Views\Twig as View;

class AppController extends Controller {
    public function index($req, $res) {
        return $this->view->render($res, 'pages/home.html');
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

    public function createList($req, $res) {
        $user = new TODOList;

        $user->title = 'Example title';
        $user->status_id = 1;

        $user->save();

        return $res->withJson(array('id' => $user->id));
    }
};
