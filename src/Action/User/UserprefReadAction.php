<?php

declare(strict_types = 1);

namespace App\Action\User;

use App\Domain\User\Service\UserprefReader;
use App\Renderer\JsonRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Action.
 */
final class UserprefReadAction
{
    private UserprefReader $userprefReader;

    private JsonRenderer $jsonRenderer;

    /**
     * The constructor.
     *
     * @param UserprefReader $userprefReader The service
     * @param JsonRenderer $jsonRenderer The responder
     */
    public function __construct(UserprefReader $userprefReader, JsonRenderer $jsonRenderer)
    {
        $this->userprefReader = $userprefReader;
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
        $userId = (int)$args['user_id'];

        // Invoke the domain (service class)
        $usertype = $this->userprefReader->getUserprefData($userId);

        // Transform result
        return $this->jsonRenderer->json($response, $usertype->transform());
    }
}
