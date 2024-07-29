<?php

declare(strict_types = 1);

namespace App\Factory;

use Psr\Container\ContainerInterface;
use Slim\App;

/**
 * Factory.
 */
final class AppFactory
{
    /**
     * The creator.
     *
     * @param ContainerInterface $container The container
     *
     * @return App The http application
     */
    public static function createInstance(ContainerInterface $container): App
    {
        $app = \Slim\Factory\AppFactory::createFromContainer($container);

        // Register routes
        (require __DIR__ . '/../../config/routes.php')($app);

        // Register middleware
        (require __DIR__ . '/../../config/middleware.php')($app);

        return $app;
    }
}
