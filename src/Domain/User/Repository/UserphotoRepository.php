<?php

declare(strict_types = 1);

namespace App\Domain\User\Repository;

use App\Domain\User\Data\UserphotoData;
use App\Support\W2\RepositoryBase;

/**
 * Repository.
 */
final class UserphotoRepository
{
    private RepositoryBase $repository;

    public const TABLE_NAME = 'photo';

    public const TABLE_KEY = 'userid';

    /**
     * The constructor.
     *
     * @param RepositoryBase $repositoryBase The basic repository handler
     */
    public function __construct(RepositoryBase $repositoryBase)
    {
        $this->repository = $repositoryBase;
    }

    /**
     * Insert userphoto row.
     *
     * @param UserphotoData $userphoto The userphoto data
     *
     * @return int The new ID
     */
    public function insertUserphoto(UserphotoData $userphoto): int
    {
        return $this->repository->insertRecord(self::TABLE_NAME, $userphoto->transform());
    }

    /**
     * Get userphoto by id.
     *
     * @param int $userId The userphoto id
     *
     * @throws \DomainException
     *
     * @return UserphotoData The userphoto
     */
    public function getUserphotoById(int $userId): UserphotoData
    {
        $record = $this->repository
            ->getRecord(self::TABLE_NAME, self::TABLE_KEY, strval($userId), UserphotoData::FIELDS);

        return new UserphotoData($record);
    }

    /**
     * Update userphoto row.
     *
     * @param UserphotoData $userphoto The userphoto
     *
     * @return void
     */
    public function updateUserphoto(UserphotoData $userphoto): void
    {
        $this->repository
            ->updateRecord(self::TABLE_NAME, self::TABLE_KEY, strval($userphoto->userid), $userphoto->transform());
    }

    /**
     * Check userphoto id.
     *
     * @param int $userId The userphoto id
     *
     * @return bool True if exists
     */
    public function existsUserphotoId(int $userId): bool
    {
        return $this->repository->existsRecord(self::TABLE_NAME, self::TABLE_KEY, strval($userId));
    }
}
