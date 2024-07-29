<?php

declare(strict_types = 1);

namespace App\Action\User;

use App\Domain\User\Service\UsertypeReader;
use App\Renderer\JsonRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Action.
 */
final class UsertypeReadAction
{
    private UsertypeReader $usertypeReader;

    private JsonRenderer $jsonRenderer;

    /**
     * The constructor.
     *
     * @param UsertypeReader $usertypeReader The service
     * @param JsonRenderer $jsonRenderer The responder
     */
    public function __construct(UsertypeReader $usertypeReader, JsonRenderer $jsonRenderer)
    {
        $this->usertypeReader = $usertypeReader;
        $this->jsonRenderer = $jsonRenderer;
    }

    /**
     * Action.
     *
     * @param ServerRequestInterface $request The request
     * @param ResponseInterface $response The response
     * @param array $args The routing arguments
     *
     * @return ResponseInterface The response
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        // Fetch parameters from the request
        $usertypeId = (int)$args['utypeid'];

        // Invoke the domain (service class)
        $usertype = $this->usertypeReader->getUsertypeData($usertypeId);

        // Transform result
        return $this->jsonRenderer->json($response, $usertype->transform());
    }
}
