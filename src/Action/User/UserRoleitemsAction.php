<?php

declare(strict_types = 1);

namespace App\Action\User;

use App\Domain\User\Service\RoleFinder;
use App\Domain\User\Service\UserReader;
use App\Domain\User\Service\UsertypeReader;
use App\Renderer\JsonRenderer;
use App\Support\BinArrayConverter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Action.
 */
final class UserRoleitemsAction
{
    private UserReader $userReader;

    private UsertypeReader $usertypeReader;

    private RoleFinder $roleFinder;

    private JsonRenderer $jsonRenderer;

    /**
     * The constructor.
     *
     * @param UserReader $userReader The user reader service
     * @param UsertypeReader $usertypeReader The usertype reader service
     * @param RoleFinder $roleFinder The role index list viewer
     * @param JsonRenderer $jsonRenderer The renderer
     */
    public function __construct(
        UserReader $userReader,
        UsertypeReader $usertypeReader,
        RoleFinder $roleFinder,
        JsonRenderer $jsonRenderer
    ) {
        $this->userReader = $userReader;
        $this->usertypeReader = $usertypeReader;
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
        $userId = (int)$args['user_id'];

        // Invoke the domain (service class)
        $user = $this->userReader->getUserData($userId);
        $usertype = $this->usertypeReader->getUsertypeData((int)$user->fk_utypeid);
        $rolesarray = BinArrayConverter::makeArrayFromBin((int)$usertype->roles);

        $roles = $this->roleFinder->findRoles($rolesarray);

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
