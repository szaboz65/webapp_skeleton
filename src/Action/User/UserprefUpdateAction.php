<?php

declare(strict_types = 1);

namespace App\Action\User;

use App\Domain\User\Service\UserprefUpdater;
use App\Renderer\JsonRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Action.
 */
final class UserprefUpdateAction
{
    private JsonRenderer $jsonRenderer;

    private UserprefUpdater $userprefUpdater;

    /**
     * The constructor.
     *
     * @param JsonRenderer $jsonRenderer The renderer
     * @param UserprefUpdater $userprefUpdater The service
     */
    public function __construct(JsonRenderer $jsonRenderer, UserprefUpdater $userprefUpdater)
    {
        $this->jsonRenderer = $jsonRenderer;
        $this->userprefUpdater = $userprefUpdater;
    }

    /**
     * Action.
     *
     * @param ServerRequestInterface $request The request
     * @param ResponseInterface $response The response
     * @param array $args The route arguments
     *
     * @return ResponseInterface The response
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        // Extract the form data from the request body
        $userId = (int)$args['user_id'];
        $data = (array)$request->getParsedBody();

        // Invoke the Domain with inputs and retain the result
        $this->userprefUpdater->updateUserpref($userId, $data);

        // Build the HTTP response
        return $this->jsonRenderer->json($response);
    }
}
