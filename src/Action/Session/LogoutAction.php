<?php

declare(strict_types = 1);

namespace App\Action\Session;

use App\Domain\Session\Service\Logouter;
use App\Renderer\JsonRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Action.
 */
final class LogoutAction
{
    private JsonRenderer $jsonRenderer;

    private Logouter $logouter;

    /**
     * The constructor.
     *
     * @param JsonRenderer $renderer The responder
     * @param Logouter $logouter The service
     */
    public function __construct(JsonRenderer $renderer, Logouter $logouter)
    {
        $this->jsonRenderer = $renderer;
        $this->logouter = $logouter;
    }

    /**
     * Action.
     *
     * @param ServerRequestInterface $request The request
     * @param ResponseInterface $response The response
     *
     * @return ResponseInterface The response
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        // Invoke the Domain
        $this->logouter->logout();

        // Build the HTTP response
        return $this->jsonRenderer->json($response, ['error' => false]);
    }
}
