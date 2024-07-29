<?php

declare(strict_types = 1);

namespace App\Action\User;

use App\Domain\User\Service\UserprefReader;
use App\Domain\User\Service\UserReader;
use App\Renderer\JsonRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Action.
 */
final class UserReadAction
{
    private UserReader $userReader;

    private UserprefReader $userprefReader;

    private JsonRenderer $jsonRenderer;

    /**
     * The constructor.
     *
     * @param UserReader $userReader The user reader service
     * @param UserprefReader $userprefReader The userpref reader service
     * @param JsonRenderer $jsonRenderer The responder
     */
    public function __construct(UserReader $userReader, UserprefReader $userprefReader, JsonRenderer $jsonRenderer)
    {
        $this->userReader = $userReader;
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
        $user = $this->userReader->getUserData($userId);
        $userpref = $this->userprefReader->getUserprefData($userId);

        // Transform result
        return $this->jsonRenderer->json($response, array_merge($user->transform(), $userpref->transform()));
    }
}
