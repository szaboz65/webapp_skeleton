<?php

declare(strict_types = 1);

namespace App\Action\DBUpdate;

use App\Domain\DBUpdate\Service\DBUpdater;
use App\Renderer\JsonRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Action.
 */
final class DBUpdateAction
{
    private DBUpdater $dbupdater;

    private JsonRenderer $jsonRenderer;

    /**
     * The constructor.
     *
     * @param DBUpdater $dbupdater The service
     * @param JsonRenderer $renderer The responder
     */
    public function __construct(DBUpdater $dbupdater, JsonRenderer $renderer)
    {
        $this->dbupdater = $dbupdater;
        $this->jsonRenderer = $renderer;
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
        // Invoke the Domain service
        $result = $this->dbupdater->doUpdate();

        // Build the HTTP response
        return $this->jsonRenderer
            ->json($response, ['files' => $result]);
    }
}
