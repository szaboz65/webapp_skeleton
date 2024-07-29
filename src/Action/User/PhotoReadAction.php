<?php

declare(strict_types = 1);

namespace App\Action\User;

use App\Domain\User\Service\PhotoReader;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Action.
 */
final class PhotoReadAction
{
    private PhotoReader $photoReader;

    /**
     * The constructor.
     *
     * @param PhotoReader $photoReader The service
     */
    public function __construct(PhotoReader $photoReader)
    {
        $this->photoReader = $photoReader;
    }

    /**
     * Action.
     *
     * @param ServerRequestInterface $request The request
     * @param ResponseInterface $response The response
     * @param array $args The routing arguments
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
        $usertype = $this->photoReader->getUserphotoData($userId);
        $response->getBody()->write((string)$usertype->photo);

        return $response;
    }
}
