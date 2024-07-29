<?php

declare(strict_types = 1);

namespace App\Factory;

use App\Handler\DefaultErrorHandler;
use App\Support\SettingsInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;
use Slim\App;
use Slim\Middleware\ErrorMiddleware;

/**
 * Factory.
 */
final class ErrorMiddlewareFactory
{
    /**
     * The creator.
     *
     * @param ContainerInterface $container The container
     *
     * @return MiddlewareInterface The ErrorMiddleware
     */
    public static function createInstance(ContainerInterface $container): MiddlewareInterface
    {
        $settings = $container->get(SettingsInterface::class)->get('error');
        $app = $container->get(App::class);

        $logger = $container->get(LoggerFactory::class)
            ->addFileHandler('error.log')
            ->createLogger();

        $errorMiddleware = new ErrorMiddleware(
            $app->getCallableResolver(),
            $app->getResponseFactory(),
            (bool)$settings['display_error_details'],
            (bool)$settings['log_errors'],
            (bool)$settings['log_error_details'],
            $logger
        );

        $errorMiddleware->setDefaultErrorHandler($container->get(DefaultErrorHandler::class));

        return $errorMiddleware;
    }
}
