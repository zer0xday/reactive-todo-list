<?php

namespace App\Middleware;
use App\Models\User;

class AuthMiddleware extends Middleware {

    public function __invoke($req, $res, $next) {
        if(isset($_SESSION['user'])) {
           return $next($req, $res);
        } else {
            return $res->withRedirect($this->container->router->pathFor('home'));
        }
    }
}