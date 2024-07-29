<?php

declare(strict_types = 1);

namespace App\Domain\User\Service;

use App\Domain\User\Data\UserprefData;
use App\Domain\User\Repository\UserprefRepository;

/**
 * Service.
 */
final class UserprefReader
{
    private UserprefRepository $repository;

    /**
     * The constructor.
     *
     * @param UserprefRepository $repository The repository
     */
    public function __construct(UserprefRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Read a userpref.
     *
     * @param int $userprefId The userpref id
     *
     * @return UserprefData The userpref data
     */
    public function getUserprefData(int $userprefId): UserprefData
    {
        // Input validation
        // ...

        // Fetch data from the database
        $userpref = $this->repository->getUserprefById($userprefId);

        return $userpref;
    }
}
