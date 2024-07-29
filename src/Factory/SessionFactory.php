<?php

declare(strict_types = 1);

namespace App\Factory;

use App\Domain\Session\Session\SessionInterface;

/**
 * Session Factory as Singleton pattern.
 */
final class SessionFactory
{
    /**
     * Hold the class instance.
     *
     * @var SessionInterface|null The instance
     */
    private static $instance;

    /**
     * Get Session Interface.
     *
     * @param array $options The options
     *
     * @return SessionInterface The session
     */
    public static function createInstance(array $options): SessionInterface
    {
        if (!isset(self::$instance)) {
            self::$instance = isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'test' ?
                new \App\Domain\Session\Session\MemorySession($options) :
                new \App\Domain\Session\Session\PhpSession($options);
        }

        return self::$instance;
    }
}
