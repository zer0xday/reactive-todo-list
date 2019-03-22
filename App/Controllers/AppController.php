<?php
namespace App\Controllers;
use App\Models\User;

use Respect\Validation\Validator as v;
use \Slim\Views\Twig as View;

class AppController extends Controller {
    public function index($req, $res) {
        return $this->view->render($res, 'pages/home.html');
    }

    public function signUp($req, $res) {
        return $this->view->render($res, 'pages/signup.html');
    }

    public function createAccount($req, $res) {
        $validator = $this->validator->validate($req, [
                'name' => v::noWhitespace()
                    ->notEmpty()
                    ->alpha(),
                'surname' => v::noWhitespace()
                    ->notEmpty()
                    ->alpha(),
                'username' => v::noWhitespace()
                    ->notEmpty()
                    ->alnum(),
                'email' => v::noWhitespace()
                    ->notEmpty()
                    ->email(),
                'password' => v::noWhitespace()
                    ->notEmpty(),
                'confirm_password' => v::noWhitespace()
                    ->notEmpty()
                    ->equals($req->getParam('password'))
            ]
        );

        if(!$validator) {
            return $res->withRedirect($this->router->pathFor('signUp'));
        }

        $user = User::create([
            'name' => $req->getParam('name'),
            'surname' => $req->getParam('surname'),
            'username' => $req->getParam('username'),
            'email' => $req->getParam('email'),
            'password_hash' => password_hash($req->getParam('confirm_password'), PASSWORD_DEFAULT)
        ]);

        return $res->withRedirect($this->router->pathFor('home'));
    }

};
