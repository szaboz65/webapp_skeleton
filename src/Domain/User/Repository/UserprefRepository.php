<?php

declare(strict_types = 1);

namespace App\Domain\User\Repository;

use App\Domain\User\Data\UserprefData;
use App\Support\W2\RepositoryBase;

/**
 * Repository.
 */
final class UserprefRepository
{
    private RepositoryBase $repository;

    public const TABLE_NAME = 'userpref';

    public const TABLE_KEY = 'upref_id';

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
     * Insert userpref row.
     *
     * @param UserprefData $userpref The userpref data
     *
     * @return int The new ID
     */
    public function insertUserpref(UserprefData $userpref): int
    {
        return $this->repository->insertRecord(self::TABLE_NAME, $userpref->transform());
    }

    /**
     * Get userpref by id.
     *
     * @param int $userprefId The userpref id
     *
     * @throws \DomainException
     *
     * @return UserprefData The userpref
     */
    public function getUserprefById(int $userprefId): UserprefData
    {
        $record = $this->repository
            ->getRecord(self::TABLE_NAME, self::TABLE_KEY, strval($userprefId), UserprefData::FIELDS);

        return new UserprefData($record);
    }

    /**
     * Update userpref row.
     *
     * @param UserprefData $userpref The userpref
     *
     * @return void
     */
    public function updateUserpref(UserprefData $userpref): void
    {
        $this->repository
            ->updateRecord(self::TABLE_NAME, self::TABLE_KEY, strval($userpref->upref_id), $userpref->transform());
    }

    /**
     * Check userpref id.
     *
     * @param int $userprefId The userpref id
     *
     * @return bool True if exists
     */
    public function existsUserprefId(int $userprefId): bool
    {
        return $this->repository->existsRecord(self::TABLE_NAME, self::TABLE_KEY, strval($userprefId));
    }
}
