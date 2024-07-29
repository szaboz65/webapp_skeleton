<?php

declare(strict_types = 1);

namespace App\Domain\User\Repository;

use App\Domain\User\Data\UsersecretData;
use App\Support\W2\RepositoryBase;

/**
 * Repository.
 */
final class UsersecretRepository
{
    private RepositoryBase $repository;

    public const TABLE_NAME = 'usersecret';

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
     * Insert usersecret row.
     *
     * @param UsersecretData $usersecret The usersecret data
     *
     * @return int
     */
    public function insertUsersecret(UsersecretData $usersecret): int
    {
        return $this->repository->insertRecord(self::TABLE_NAME, $usersecret->transform());
    }

    /**
     * Update usersecret row.
     *
     * @param UsersecretData $usersecret The usersecret data
     *
     * @return void
     */
    public function updateUsersecretInactive(UsersecretData $usersecret): void
    {
        $data = $usersecret->transform();
        $this->repository->getQueryFactory()
            ->newUpdate(self::TABLE_NAME, ['inactive' => $data['inactive']])
            ->andWhere([
                'userid' => $data['userid'],
                'expire' => $data['expire']])
            ->execute();
    }
}
