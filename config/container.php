<?php

declare(strict_types = 1);

use App\Domain\Session\Session\SessionInterface;
use App\Factory\LoggerFactory;
use App\Support\Mailer;
use App\Support\SettingsInterface;
use App\Support\TemplateEngine;
use Cake\Database\Connection;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Log\LoggerInterface;
use Selective\BasePath\BasePathMiddleware;
use Slim\App;
use Slim\Interfaces\RouteParserInterface;
use Slim\Middleware\ErrorMiddleware;
use Symfony\Component\Console\Application;

return [
    // Application settings
    SettingsInterface::class => function (ContainerInterface $container) {
        return \App\Factory\SettingsFactory::createInstance($container);
    },

    App::class => function (ContainerInterface $container) {
        return \App\Factory\AppFactory::createInstance($container);
    },

    // HTTP factories
    ResponseFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    ServerRequestFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    StreamFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    UploadedFileFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    UriFactoryInterface::class => function (ContainerInterface $container) {
        return $container->get(Psr17Factory::class);
    },

    // The Slim RouterParser
    RouteParserInterface::class => function (ContainerInterface $container) {
        return $container->get(App::class)->getRouteCollector()->getRouteParser();
    },

    // The logger factory
    LoggerFactory::class => function (ContainerInterface $container) {
        return new LoggerFactory($container->get(SettingsInterface::class)->get('logger'));
    },

    LoggerInterface::class => function (ContainerInterface $container) {
        return \App\Factory\AppLoggerFactory::createInstance($container);
    },

    // Session
    SessionInterface::class => function (ContainerInterface $container) {
        return \App\Factory\SessionFactory::createInstance($container->get(SettingsInterface::class)->get('session'));
    },

    // Database connection
    Connection::class => function (ContainerInterface $container) {
        return new Connection($container->get(SettingsInterface::class)->get('db'));
    },

    PDO::class => function (ContainerInterface $container) {
        $db = $container->get(Connection::class);
        $driver = $db->getDriver();
        $driver->connect();

        return $driver->getConnection();
    },

    Application::class => function (ContainerInterface $container) {
        return \App\Factory\ApplicationFactory::createInstance($container);
    },

    TemplateEngine::class => function (ContainerInterface $container) {
        $engine = new TemplateEngine();
        $engine->setTemplatePath($container->get(SettingsInterface::class)->get('template'));

        return $engine;
    },

    Mailer::class => function (ContainerInterface $container) {
        return new Mailer($container->get(SettingsInterface::class)->get('mail'));
    },

    // middlewares
    BasePathMiddleware::class => function (ContainerInterface $container) {
        return new BasePathMiddleware($container->get(App::class));
    },

    ErrorMiddleware::class => function (ContainerInterface $container) {
        return \App\Factory\ErrorMiddlewareFactory::createInstance($container);
    },
];
