<?php

declare(strict_types = 1);

namespace App\Action\Session;

use App\Domain\Session\Service\SessionReader;
use App\Domain\Session\Session\SessionInterface;
use App\Renderer\JsonRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Action.
 */
final class SessionReadAction
{
    private SessionInterface $session;

    private SessionReader $sessionReader;

    private JsonRenderer $jsonRenderer;

    /**
     * The constructor.
     *
     * @param SessionInterface $session The session
     * @param SessionReader $sessionReader The service
     * @param JsonRenderer $jsonRenderer The responder
     */
    public function __construct(SessionInterface $session, SessionReader $sessionReader, JsonRenderer $jsonRenderer)
    {
        $this->session = $session;
        $this->sessionReader = $sessionReader;
        $this->jsonRenderer = $jsonRenderer;
    }

    /**
     * Action.
     *
     * @param ServerRequestInterface $request The request
     * @param ResponseInterface $response The response
     *
     * @return ResponseInterface The response
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        // get userid from session
        $userId = $this->session->get('userId');

        // Invoke the domain (service class)
        $sessionData = $this->sessionReader->getSessionData($userId);

        // Transform result
        return $this->jsonRenderer->json($response, $sessionData->transform());
    }
}
