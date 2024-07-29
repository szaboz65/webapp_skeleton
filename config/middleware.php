<?php

use App\Middleware\SessionMiddleware;
use App\Middleware\ValidationMiddleware;
use Selective\BasePath\BasePathMiddleware;
use Slim\App;
use Slim\Middleware\ErrorMiddleware;

return function (App $app) {
    $app->add(SessionMiddleware::class);
    $app->addBodyParsingMiddleware();
    $app->add(ValidationMiddleware::class);
    $app->addRoutingMiddleware();
    $app->add(BasePathMiddleware::class);
    $app->add(ErrorMiddleware::class);
};
