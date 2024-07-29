<?php

declare(strict_types = 1);

namespace App\Domain\User\Repository;

use App\Domain\User\Data\UserData;
use App\Support\W2\RepositoryBase;

/**
 * Repository.
 */
final class UserRepository
{
    private RepositoryBase $repository;

    public const TABLE_NAME = 'user';

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
     * Insert user row.
     *
     * @param UserData $user The user data
     *
     * @return int The new ID
     */
    public function insertUser(UserData $user): int
    {
        return $this->repository->insertRecord(self::TABLE_NAME, $user->transform());
    }

    /**
     * Get user by id.
     *
     * @param int $userId The user id
     *
     * @throws \DomainException
     *
     * @return UserData The user
     */
    public function getUserById(int $userId): UserData
    {
        $record = $this->repository->getRecord(self::TABLE_NAME, self::TABLE_KEY, strval($userId), UserData::FIELDS);

        return new UserData($record);
    }

    /**
     * Update user row.
     *
     * @param UserData $user The user
     *
     * @return void
     */
    public function updateUser(UserData $user): void
    {
        $this->repository->updateRecord(self::TABLE_NAME, self::TABLE_KEY, strval($user->userid), $user->transform());
    }

    /**
     * Check user id.
     *
     * @param int $userId The user id
     *
     * @return bool True if exists
     */
    public function existsUserId(int $userId): bool
    {
        return $this->repository->existsRecord(self::TABLE_NAME, self::TABLE_KEY, strval($userId));
    }

    /**
     * Delete user row.
     *
     * @param int $userId The user id
     *
     * @return void
     */
    public function deleteUserById(int $userId): void
    {
        $this->repository->deleteRecord(self::TABLE_NAME, self::TABLE_KEY, strval($userId));
    }
}
