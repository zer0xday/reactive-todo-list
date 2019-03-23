<?php

namespace App\Middleware;
use App\Models\User;

class LoginMiddleware extends Middleware {

    public function __invoke($req, $res, $next) {
        if(isset($_SESSION['user'])) {
            $this->container->view->getEnvironment()->addGlobal(
                'user',
                User::find($_SESSION['user'])
            );
        }
        return $next($req, $res);
    }
}