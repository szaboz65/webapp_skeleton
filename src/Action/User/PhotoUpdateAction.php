<?php

declare(strict_types = 1);

namespace App\Action\User;

use App\Domain\User\Service\PhotoUpdater;
use App\Renderer\JsonRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Action.
 */
final class PhotoUpdateAction
{
    private JsonRenderer $jsonRenderer;

    private PhotoUpdater $photoUpdater;

    /**
     * The constructor.
     *
     * @param JsonRenderer $jsonRenderer The renderer
     * @param PhotoUpdater $photoUpdater The service
     */
    public function __construct(JsonRenderer $jsonRenderer, PhotoUpdater $photoUpdater)
    {
        $this->jsonRenderer = $jsonRenderer;
        $this->photoUpdater = $photoUpdater;
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
        $this->photoUpdater->updatePhoto($userId, $data);

        // Build the HTTP response
        return $this->jsonRenderer->json($response);
    }
}
