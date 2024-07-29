<?php

declare(strict_types = 1);

namespace App\Domain\User\Repository;

use App\Domain\User\Data\UserpassresetData;
use App\Support\W2\RepositoryBase;

/**
 * Repository.
 */
final class UserpassresetRepository
{
    private RepositoryBase $repository;

    public const TABLE_NAME = 'pass_reset';

    public const TABLE_KEY = 'userid';

    /**
     * The constructor.
     *
     * @param RepositoryBase $repositoryBase The basic repo handler
     */
    public function __construct(RepositoryBase $repositoryBase)
    {
        $this->repository = $repositoryBase;
    }

    /**
     * Insert userpassreset row.
     *
     * @param UserpassresetData $userpassreset The userpassreset data
     *
     * @return int The new ID
     */
    public function insertUserpassreset(UserpassresetData $userpassreset): int
    {
        return $this->repository->insertRecord(self::TABLE_NAME, $userpassreset->transform());
    }

    /**
     * Get userpassreset by id.
     *
     * @param int $userId The user id
     *
     * @throws \DomainException
     *
     * @return UserpassresetData The user
     */
    public function getUserpassresetById(int $userId): UserpassresetData
    {
        $record = $this->repository->getRecord(
            self::TABLE_NAME,
            self::TABLE_KEY,
            strval($userId),
            UserpassresetData::FIELDS
        );

        return new UserpassresetData($record);
    }

    /**
     * Update userpassreset row.
     *
     * @param UserpassresetData $userpassreset The userpassreset
     *
     * @return void
     */
    public function updateUserpassreset(UserpassresetData $userpassreset): void
    {
        $this->repository->updateRecord(
            self::TABLE_NAME,
            self::TABLE_KEY,
            strval($userpassreset->userid),
            $userpassreset->transform()
        );
    }

    /**
     * Check userpassreset id.
     *
     * @param int $userId The user id
     *
     * @return bool True if exists
     */
    public function existsUserpassresetId(int $userId): bool
    {
        return $this->repository->existsRecord(self::TABLE_NAME, self::TABLE_KEY, strval($userId));
    }

    /**
     * Delete userpassreset row.
     *
     * @param int $userId The user id
     *
     * @return void
     */
    public function deleteUserpassresetById(int $userId): void
    {
        $this->repository->deleteRecord(self::TABLE_NAME, self::TABLE_KEY, strval($userId));
    }
}
