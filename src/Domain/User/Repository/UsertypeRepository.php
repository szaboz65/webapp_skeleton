<?php

declare(strict_types = 1);

namespace App\Domain\User\Repository;

use App\Domain\User\Data\UsertypeData;
use App\Support\W2\RepositoryBase;

/**
 * Repository.
 */
final class UsertypeRepository
{
    private RepositoryBase $repository;

    public const TABLE_NAME = 'usertype';

    public const TABLE_KEY = 'utypeid';

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
     * Get usertype by id.
     *
     * @param int $usertypeId The usertype id
     *
     * @throws \DomainException
     *
     * @return UsertypeData The usertype
     */
    public function getUsertypeById(int $usertypeId): UsertypeData
    {
        $record = $this->repository
            ->getRecord(self::TABLE_NAME, self::TABLE_KEY, strval($usertypeId), UsertypeData::FIELDS);

        return new UsertypeData($record);
    }

    /**
     * Update usertype row.
     *
     * @param UsertypeData $usertype The usertype
     *
     * @return void
     */
    public function updateUsertype(UsertypeData $usertype): void
    {
        $this->repository
            ->updateRecord(self::TABLE_NAME, self::TABLE_KEY, strval($usertype->utypeid), $usertype->transform());
    }

    /**
     * Check usertype id.
     *
     * @param int $usertypeId The usertype id
     *
     * @return bool True if exists
     */
    public function existsUsertypeId(int $usertypeId): bool
    {
        return $this->repository->existsRecord(self::TABLE_NAME, self::TABLE_KEY, strval($usertypeId));
    }
}
