<?php

declare(strict_types = 1);

namespace App\Action\User;

use App\Domain\User\Service\UsertypeFinder;
use App\Renderer\JsonRenderer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Action.
 */
final class UsertypeItemsAction
{
    private UsertypeFinder $usertypeFinder;

    private JsonRenderer $jsonRenderer;

    /**
     * The constructor.
     *
     * @param UsertypeFinder $usertypeFinder The usertype list viewer
     * @param JsonRenderer $jsonRenderer The renderer
     */
    public function __construct(UsertypeFinder $usertypeFinder, JsonRenderer $jsonRenderer)
    {
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
        // Optional: Pass parameters from the request to the findRoles method
        $roles = $this->usertypeFinder->findUsertypes();

        return $this->transform($response, $roles);
    }

    /**
     * Transform to json response.
     * This could also be done within a specific Responder class.
     *
     * @param ResponseInterface $response The response
     * @param array $rusertypes The roles
     *
     * @return ResponseInterface The response
     */
    private function transform(ResponseInterface $response, array $rusertypes): ResponseInterface
    {
        $usertypeList = [];

        foreach ($rusertypes as $usertype) {
            $usertypeList[] = $usertype->transformItem();
        }

        return $this->jsonRenderer->json($response, $usertypeList);
    }
}
