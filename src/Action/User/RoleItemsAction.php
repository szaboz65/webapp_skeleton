<?php

declare(strict_types = 1);

namespace App\Action\User;

use App\Domain\User\Service\RoleFinder;
use App\Renderer\JsonRenderer;
use App\Support\BinArrayConverter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Action.
 */
final class RoleItemsAction
{
    private RoleFinder $roleFinder;

    private JsonRenderer $jsonRenderer;

    /**
     * The constructor.
     *
     * @param RoleFinder $roleFinder The role index list viewer
     * @param JsonRenderer $jsonRenderer The renderer
     */
    public function __construct(RoleFinder $roleFinder, JsonRenderer $jsonRenderer)
    {
        $this->roleFinder = $roleFinder;
        $this->jsonRenderer = $jsonRenderer;
    }

    /**
     * Action.
     *
     * @param ServerRequestInterface $request The request
     * @param ResponseInterface $response The response
     * @param array $args The args
     *
     * @return ResponseInterface The response
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $args
    ): ResponseInterface {
        // Fetch parameters from the request
        $roles = null;
        if (isset($args['roles'])) {
            $roles = BinArrayConverter::makeArrayFromBin((int)$args['roles']);
        }
        // Optional: Pass parameters from the request to the findRoles method
        $roles = $this->roleFinder->findRoles($roles);

        return $this->transform($response, $roles);
    }

    /**
     * Transform to json response.
     * This could also be done within a specific Responder class.
     *
     * @param ResponseInterface $response The response
     * @param array $roles The roles
     *
     * @return ResponseInterface The response
     */
    private function transform(ResponseInterface $response, array $roles): ResponseInterface
    {
        $roleList = [];

        foreach ($roles as $role) {
            $roleList[] = $role->transformItem();
        }

        return $this->jsonRenderer->json($response, $roleList);
    }
}
