<?php

declare(strict_types = 1);

namespace App\Action\User;

use App\Domain\User\Service\RoleFinder;
use App\Renderer\JsonRenderer;
use App\Support\CollectionTransformer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Action.
 */
final class RoleFindAction
{
    private RoleFinder $roleFinder;

    private CollectionTransformer $collectionTransformer;

    private JsonRenderer $jsonRenderer;

    /**
     * The constructor.
     *
     * @param RoleFinder $roleFinder The role index list viewer
     * @param CollectionTransformer $collectionTransformer The transformer
     * @param JsonRenderer $jsonRenderer The renderer
     */
    public function __construct(
        RoleFinder $roleFinder,
        CollectionTransformer $collectionTransformer,
        JsonRenderer $jsonRenderer
    ) {
        $this->roleFinder = $roleFinder;
        $this->collectionTransformer = $collectionTransformer;
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
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        // Optional: Pass parameters from the request to the findRoles method
        $roles = $this->roleFinder->findRoles();
        $totals = count($roles);

        return $this->jsonRenderer->json($response, $this->collectionTransformer->transform($roles, $totals));
    }
}
