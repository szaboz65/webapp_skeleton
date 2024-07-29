<?php

declare(strict_types = 1);

namespace App\Factory;

use App\Support\Settings;
use App\Support\SettingsInterface;
use Psr\Container\ContainerInterface;

/**
 * Factory.
 */
final class SettingsFactory
{
    /**
     * The creator.
     *
     * @param ContainerInterface $container The container
     *
     * @return SettingsInterface The settings reader
     */
    public static function createInstance(ContainerInterface $container): SettingsInterface
    {
        $settings_data = require __DIR__ . '/../../config/settings.php';
        $settings = new Settings($settings_data);

        return $settings;
    }
}
