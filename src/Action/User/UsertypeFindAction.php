<?php

declare(strict_types = 1);

namespace App\Action\User;

use App\Domain\User\Service\RoleFinder;
use App\Domain\User\Service\UsertypeFinder;
use App\Renderer\JsonRenderer;
use App\Support\BinArrayConverter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Action.
 */
final class UsertypeFindAction
{
    private RoleFinder $roleFinder;

    private UsertypeFinder $usertypeFinder;

    private JsonRenderer $jsonRenderer;

    /**
     * The constructor.
     *
     * @param RoleFinder $roleFinder The role list finder
     * @param UsertypeFinder $usertypeFinder The usertype list findr
     * @param JsonRenderer $jsonRenderer The renderer
     */
    public function __construct(RoleFinder $roleFinder, UsertypeFinder $usertypeFinder, JsonRenderer $jsonRenderer)
    {
        $this->roleFinder = $roleFinder;
        $this->usertypeFinder = $usertypeFinder;
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
        // Optional: Pass parameters from the request to the findUsertypes method
        $usertypes = $this->usertypeFinder->findUsertypes();

        return $this->jsonRenderer->json($response, $this->transform($usertypes));
    }

    /**
     * Transform collection to array.
     *
     * @param array $usertypes The usertypes
     *
     * @return array The array
     */
    private function transform(array $usertypes): array
    {
        $usertypeList = [];

        foreach ($usertypes as $usertype) {
            $roles = $this->roleFinder->findRoles(BinArrayConverter::makeArrayFromBin($usertype->roles));
            $usertypeList[] = array_merge($usertype->transform(), ['role' => $roles]);
        }

        return $usertypeList;
    }
}
