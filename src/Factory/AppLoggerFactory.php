<?php

declare(strict_types = 1);

namespace App\Factory;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

/**
 * Factory.
 */
final class AppLoggerFactory
{
    /**
     * The creator.
     *
     * @param ContainerInterface $container The container
     *
     * @return LoggerInterface The logger
     */
    public static function createInstance(ContainerInterface $container): LoggerInterface
    {
        $logger = $container->get(LoggerFactory::class)
            ->addFileHandler('app.log')
            ->createLogger();

        return $logger;
    }
}
