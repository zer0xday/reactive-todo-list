<?php

namespace App\Middleware;

class CSRFProtectionMiddleware extends Middleware {
    public function __invoke($req, $res, $next) {
        $this->container->view->getEnvironment()->addGlobal('csrf', [
            'fields' =>
            '
                <input type="hidden" name="'.$this->container->csrf->getTokenNameKey().'" value="'.$this->container->csrf->getTokenName().'">
                <input type="hidden" name="'.$this->container->csrf->getTokenValueKey().'" value="'.$this->container->csrf->getTokenValue().'">
            '
        ]);

        return $next($req, $res);
    }
};