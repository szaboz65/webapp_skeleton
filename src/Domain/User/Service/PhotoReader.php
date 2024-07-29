<?php

declare(strict_types = 1);

namespace App\Domain\User\Service;

use App\Domain\User\Data\UserphotoData;
use App\Domain\User\Repository\UserphotoRepository;

/**
 * Service.
 */
final class PhotoReader
{
    private UserphotoRepository $repository;

    /**
     * The constructor.
     *
     * @param UserphotoRepository $repository The repository
     */
    public function __construct(UserphotoRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Read a userphoto.
     *
     * @param int $userId The userphoto id
     *
     * @return UserphotoData The userphoto data
     */
    public function getUserphotoData(int $userId): UserphotoData
    {
        // Fetch data from the database
        return $this->repository->getUserphotoById($userId);
    }
}
