<?php
// return old input when validation fails
$app->add(new \App\Middleware\OldInputMiddleware($container));
// validate forms
$app->add(new \App\Middleware\FormErrorValidation($container));
// check CSRF values
// $app->add(new \App\Middleware\CSRFProtectionMiddleware($container));
// middleware login authorization
$app->add(new \App\Middleware\LoginMiddleware($container));
