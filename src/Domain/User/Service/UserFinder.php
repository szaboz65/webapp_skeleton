<?php

declare(strict_types = 1);

namespace App\Domain\User\Service;

use App\Domain\User\Data\UserFindData;
use App\Domain\User\Repository\UserFinderRepository;
use App\Support\W2\Request;
use App\Support\W2\RequestParser;
use App\Support\W2\SearchFactory;

/**
 * Service.
 */
final class UserFinder
{
    private UserFinderRepository $repository;

    /**
     * The constructor.
     *
     * @param UserFinderRepository $repository The repository
     */
    public function __construct(UserFinderRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get count of records.
     *
     * @return int
     */
    public function getTotal(): int
    {
        return $this->repository->getTotal();
    }

    /**
     * Find users.
     *
     * @param array $data The request data
     *
     * @return UserFindData[] A list of users
     */
    public function findUsers(array $data): array
    {
        // Input validation
        $request = (new RequestParser())->parseRequest($data);

        return $this->repository->findUsers($request);
    }

    /**
     * Find users by email.
     *
     * @param string $email The email
     * @param int|null $excludeUserId The userid which exclude in the finding
     *
     * @return UserFindData[] A list of users
     */
    public function findUsersByEmail(string $email, ?int $excludeUserId = null): array
    {
        $request = (new Request())
            ->addSearch(SearchFactory::createEQ('email', trim($email)))
            ->addSearch(SearchFactory::createEQ('inactive', 0));

        if ($excludeUserId) {
            $request->addSearch(SearchFactory::createNE('userid', $excludeUserId));
        }

        return $this->repository->findUsers($request);
    }
}
