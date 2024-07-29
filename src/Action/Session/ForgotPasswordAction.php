<?php

declare(strict_types = 1);

namespace App\Action\Session;

use App\Domain\Session\Service\ForgotPassword;
use App\Renderer\JsonRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Action.
 */
final class ForgotPasswordAction
{
    private JsonRenderer $jsonRenderer;

    private ForgotPassword $forgotPassword;

    /**
     * The constructor.
     *
     * @param JsonRenderer $renderer The responder
     * @param ForgotPassword $forgotPassword The service
     */
    public function __construct(JsonRenderer $renderer, ForgotPassword $forgotPassword)
    {
        $this->jsonRenderer = $renderer;
        $this->forgotPassword = $forgotPassword;
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
        // Extract the form data from the request body
        $data = (array)$request->getParsedBody();

        // Invoke the Domain with inputs and retain the result
        $this->forgotPassword->forgot($data);

        // Build the HTTP response
        return $this->jsonRenderer->json($response, ['error' => false]);
    }
}
