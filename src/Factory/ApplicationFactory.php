<?php

declare(strict_types = 1);

namespace App\Factory;

use App\Support\SettingsInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputOption;

/**
 * Factory.
 */
final class ApplicationFactory
{
    /**
     * The creator.
     *
     * @param ContainerInterface $container The container
     *
     * @return Application The console application
     */
    public static function createInstance(ContainerInterface $container)
    {
        $application = new Application();

        $application->getDefinition()->addOption(
            new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'development')
        );

        foreach ($container->get(SettingsInterface::class)->get('commands') as $class) {
            $application->add($container->get($class));
        }

        return $application;
    }
}
