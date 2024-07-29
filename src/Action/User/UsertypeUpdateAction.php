<?php

declare(strict_types = 1);

namespace App\Action\User;

use App\Domain\User\Service\UsertypeUpdater;
use App\Renderer\JsonRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Action.
 */
final class UsertypeUpdateAction
{
    private JsonRenderer $jsonRenderer;

    private UsertypeUpdater $usertypeUpdater;

    /**
     * The constructor.
     *
     * @param JsonRenderer $jsonRenderer The renderer
     * @param UsertypeUpdater $usertypeUpdater The service
     */
    public function __construct(JsonRenderer $jsonRenderer, UsertypeUpdater $usertypeUpdater)
    {
        $this->jsonRenderer = $jsonRenderer;
        $this->usertypeUpdater = $usertypeUpdater;
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
        $usertypeId = (int)$args['utypeid'];
        $data = (array)$request->getParsedBody();

        // Invoke the Domain with inputs and retain the result
        $this->usertypeUpdater->updateUsertype($usertypeId, $data);

        // Build the HTTP response
        return $this->jsonRenderer->json($response, ['error' => false]);
    }
}
