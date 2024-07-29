<?php

declare(strict_types = 1);

namespace App\Domain\User\Service;

use App\Domain\User\Data\UsertypeData;
use App\Domain\User\Repository\UsertypeRepository;

/**
 * Service.
 */
final class UsertypeReader
{
    private UsertypeRepository $repository;

    /**
     * The constructor.
     *
     * @param UsertypeRepository $repository The repository
     */
    public function __construct(UsertypeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Read a usertype.
     *
     * @param int $usertypeId The usertype id
     *
     * @return UsertypeData The usertype data
     */
    public function getUsertypeData(int $usertypeId): UsertypeData
    {
        // Input validation
        // ...

        // Fetch data from the database
        $usertype = $this->repository->getUsertypeById($usertypeId);

        return $usertype;
    }
}
