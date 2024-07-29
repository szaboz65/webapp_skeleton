<?php

declare(strict_types = 1);

namespace App\Middleware;

use App\Domain\Session\Service\Authorizer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Routing\RouteContext;

/**
 * A PSR-15 Session Middleware.
 */
final class SessionMiddleware implements MiddlewareInterface
{
    /**
     * @var Authorizer
     */
    private $authorizer;

    /**
     * Constructor.
     *
     * @param Authorizer $authorizer The authorizer handler
     */
    public function __construct(Authorizer $authorizer)
    {
        $this->authorizer = $authorizer;
    }

    /**
     * Invoke middleware.
     *
     * @param ServerRequestInterface $request The request
     * @param RequestHandlerInterface $handler The handler
     *
     * @return ResponseInterface The response
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $routeContext = RouteContext::fromRequest($request);
        $route = $routeContext->getRoute();
        if (empty($route)) {
            throw new HttpNotFoundException($request);
        }

        if ($this->isAuthorizationNeeded($route->getName()) && !$this->authorizer->isAuthorized()) {
            throw new HttpUnauthorizedException($request);
        }

        $response = $handler->handle($request);

        return $response;
    }

    /**
     * Check if authorization is needed.
     * The algorithm can be extended if need!
     *
     * @param string|null $routeName The route name from the routes.php
     *
     * @return bool
     */
    private function isAuthorizationNeeded(?string $routeName): bool
    {
        return empty($routeName);
    }
}
