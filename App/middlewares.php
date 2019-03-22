<?php
// validate forms
$app->add(new \App\Middleware\FormErrorValidation($container));
// return old input when validation fails
$app->add(new \App\Middleware\OldInputMiddleware($container));
