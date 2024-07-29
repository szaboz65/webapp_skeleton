<?php

declare(strict_types = 1);

namespace App\Action\User;

use App\Domain\User\Service\UserFinder;
use App\Renderer\JsonRenderer;
use App\Support\CollectionTransformer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Action.
 */
final class UserFindAction
{
    private UserFinder $userFinder;

    private CollectionTransformer $collectionTransformer;

    private JsonRenderer $jsonRenderer;

    /**
     * The constructor.
     *
     * @param UserFinder $userFinder The user index list viewer
     * @param CollectionTransformer $collectionTransformer The transformer
     * @param JsonRenderer $jsonRenderer The renderer
     */
    public function __construct(
        UserFinder $userFinder,
        CollectionTransformer $collectionTransformer,
        JsonRenderer $jsonRenderer
    ) {
        $this->userFinder = $userFinder;
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
        // Extract the form data from the request
        $params = [];
        $data = $request->getQueryParams();
        if (isset($data['request'])) {
            $params = (array)json_decode($data['request']);
        }

        // Invoke the Domain with inputs and retain the result
        $users = $this->userFinder->findUsers($params);
        $totals = $this->userFinder->getTotal();

        return $this->jsonRenderer->json($response, $this->collectionTransformer->transform($users, $totals));
    }
}
