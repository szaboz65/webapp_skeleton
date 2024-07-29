<?php

declare(strict_types = 1);

namespace App\Domain\User\Repository;

use App\Domain\User\Data\UsersessionData;
use App\Support\W2\RepositoryBase;

/**
 * Repository.
 */
final class UsersessionRepository
{
    private RepositoryBase $repository;

    public const TABLE_NAME = 'usersession';

    public const TABLE_KEY = 'ses_userid';

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
     * @param UsersessionData $usersessionData The usersession data
     *
     * @return int The new ID
     */
    public function insertUsersession(UsersessionData $usersessionData): int
    {
        return $this->repository->insertRecord(self::TABLE_NAME, $usersessionData->transform());
    }

    /**
     * Get session by id.
     *
     * @param int $userId The user id
     *
     * @throws \DomainException
     *
     * @return UsersessionData The usersession
     */
    public function getUsersessionById(int $userId): UsersessionData
    {
        $record = $this->repository
            ->getRecord(self::TABLE_NAME, self::TABLE_KEY, strval($userId), UsersessionData::FIELDS);

        return new UsersessionData($record);
    }

    /**
     * Update usersession row.
     *
     * @param UsersessionData $user The usersession
     *
     * @return void
     */
    public function updateUsersession(UsersessionData $user): void
    {
        $this->repository->updateRecord(self::TABLE_NAME, self::TABLE_KEY, strval($user->userid), $user->transform());
    }

    /**
     * Check usersession id.
     *
     * @param int $userId The user id
     *
     * @return bool True if exists
     */
    public function existsUsersessionId(int $userId): bool
    {
        return $this->repository->existsRecord(self::TABLE_NAME, self::TABLE_KEY, strval($userId));
    }

    /**
     * Delete usersession row.
     *
     * @param int $userId The user id
     *
     * @return void
     */
    public function deleteUsersessionById(int $userId): void
    {
        $this->repository->deleteRecord(self::TABLE_NAME, self::TABLE_KEY, strval($userId));
    }

    /**
     * Update usersession->lastlogin.
     *
     * @param int $userId The userid
     * @param string $lastactive The lastlogin datetime
     *
     * @return void
     */
    public function updateLastactive(int $userId, string $lastactive): void
    {
        $record = ['ses_userid' => $userId, 'ses_lastactive' => $lastactive];
        $this->repository->updateRecord(self::TABLE_NAME, self::TABLE_KEY, strval($userId), $record);
    }
}
