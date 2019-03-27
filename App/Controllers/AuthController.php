<?php

namespace App\Controllers;

use App\Models\User;
use Respect\Validation\Validator as v;

class AuthController extends Controller {

    public function login($req, $res) {
        $validator = $this->validator->validate($req, [
            'username' => v::noWhiteSpace()
                ->notEmpty(),
            'password' => v::noWhiteSpace()
                ->notEmpty(),
        ]);

        if(!$validator) {
            $res->withRedirect($this->router->pathFor('home'));
        }

        $user = $this->auth->loginAttempt(
            $req->getParam('username'),
            $req->getParam('password')
        );

        if(!$user) {
            $this->view->getEnvironment()->addGlobal('doesNotExist', 'User does not exist');
        }

        return $user
            ? $res->withRedirect($this->router->pathFor('main'))
            : $res->withRedirect($this->router->pathFor('home'));
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

    public function editAccount($req, $res) {
        $validatorRules = [
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

        ];
        $user = User::find($_SESSION['user']);
        $oldPass = $req->getParam('old_password');

        if($oldPass !== '') {
            $passwordCheck = $this->validator->validatePassword($req, $req->getParam('old_password'), $user->password_hash);
            if($passwordCheck) {
                $validatorRules +=  [
                    'old_password' => v::noWhiteSpace()->notEmpty(),
                    'password' => v::noWhitespace()->notEmpty(),
                    'confirm_password' => v::noWhitespace()->equals($req->getParam('password'))->notEmpty()
                ];
                $newPasswordHash = password_hash($req->getParam('confirm_password'), PASSWORD_DEFAULT);
            } else {
                return $res->withRedirect($this->router->pathFor('account'));
            }
        }

        $validator = $this->validator->validate($req, $validatorRules);

        if(!$validator) {
            return $res->withRedirect($this->router->pathFor('account'));
        }

        $user->name = $req->getParam('name');
        $user->surname = $req->getParam('surname');
        $user->username = $req->getParam('username');
        $user->email = $req->getParam('email');
        if(isset($newPasswordHash)) {
            $user->password_hash = $newPasswordHash;
        }

        $user->save();

        return $res->withRedirect($this->router->pathFor('main'));
    }

    public function logout($req, $res) {
        $logout = $this->auth->logout();
        return $res->withRedirect($this->router->pathFor('home'));
    }
};