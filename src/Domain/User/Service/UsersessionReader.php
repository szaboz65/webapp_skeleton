<?php

declare(strict_types = 1);

namespace App\Domain\User\Service;

use App\Domain\User\Data\UsersessionData;
use App\Domain\User\Repository\UsersessionRepository;

/**
 * Service.
 */
final class UsersessionReader
{
    private UsersessionRepository $repository;

    /**
     * The constructor.
     *
     * @param UsersessionRepository $repository The repository
     */
    public function __construct(UsersessionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Read a usersession.
     *
     * @param int $userId The user id
     *
     * @return UsersessionData The usersession data
     */
    public function getUsersessionData(int $userId): UsersessionData
    {
        // Input validation
        // ...

        // Fetch data from the database
        $usersession = $this->repository->getUsersessionById($userId);

        return $usersession;
    }
}
