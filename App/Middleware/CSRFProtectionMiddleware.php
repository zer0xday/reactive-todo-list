<?php

namespace App\Middleware;

class CSRFProtectionMiddleware extends Middleware {
    public function __invoke($req, $res, $next) {
        $this->container->view->getEnvironment()->addGlobal('csrf', [
            'tokenKey' => $this->container->csrf->getTokenValueKey(),
            'nameKey' => $this->container->csrf->getTokenNameKey(),
            'tokenName' => $this->container->csrf->getTokenName(),
            'tokenValue' => $this->container->csrf->getTokenValue()
        ]);

        return $next($req, $res);
    }
};