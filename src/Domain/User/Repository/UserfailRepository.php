<?php

declare(strict_types = 1);

namespace App\Domain\User\Repository;

use App\Domain\User\Data\UserfailData;
use App\Support\W2\RepositoryBase;

/**
 * Repository.
 */
final class UserfailRepository
{
    private RepositoryBase $repository;

    public const TABLE_NAME = 'userfail';

    public const TABLE_KEY = 'fail_userid';

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
     * Insert userfail row.
     *
     * @param UserfailData $user The userfail data
     *
     * @return int The new ID
     */
    public function insertUserfail(UserfailData $user): int
    {
        return $this->repository->insertRecord(self::TABLE_NAME, $user->transform());
    }

    /**
     * Delete userfail row.
     *
     * @param int $userId The user id
     *
     * @return void
     */
    public function deleteUserfailById(int $userId): void
    {
        $this->repository->deleteRecord(self::TABLE_NAME, self::TABLE_KEY, strval($userId));
    }
}
