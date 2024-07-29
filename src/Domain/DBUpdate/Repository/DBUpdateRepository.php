<?php

declare(strict_types = 1);

namespace App\Domain\DBUpdate\Repository;

use App\Domain\DBUpdate\Data\DBUpdateData;
use App\Factory\QueryFactory;

/**
 * Repository.
 */
class DBUpdateRepository
{
    private QueryFactory $queryFactory;

    public const TABLE_NAME = 'dbupdate';

    public const TABLE_KEY = 'up_id';

    /**
     * The constructor.
     *
     * @param QueryFactory $queryFactory The query factory
     */
    public function __construct(QueryFactory $queryFactory)
    {
        $this->queryFactory = $queryFactory;
    }

    /**
     * Insert dbupdate row.
     *
     * @param DBUpdateData $dbupdate The dbupdate data
     *
     * @return int The new ID
     */
    public function insert(DBUpdateData $dbupdate): int
    {
        return (int)$this->queryFactory->newInsert(self::TABLE_NAME, $dbupdate->transform())
            ->execute()
            ->lastInsertId();
    }

    /**
     * Get dbupdate by id.
     *
     * @param int $id The dbupdate id
     *
     * @throws \DomainException
     *
     * @return DBUpdateData The data
     */
    public function getById(int $id): DBUpdateData
    {
        $query = $this->queryFactory->newSelect(self::TABLE_NAME);
        $query->select(
            [
                'up_id',
                'up_version',
                'up_description',
                'up_releasedate',
                'up_updatedate',
            ]
        );

        $query->andWhere([self::TABLE_KEY => $id]);

        $row = $query->execute()->fetch('assoc');

        if (!$row) {
            throw new \DomainException(sprintf('DBUpdate not found: %s', $id));
        }

        return new DBUpdateData($row);
    }

    /**
     * Check dbupdate version.
     *
     * @param string $version The dbupdate.version
     *
     * @return bool True if exists
     */
    public function existsVersion(string $version): bool
    {
        $query = $this->queryFactory->newSelect(self::TABLE_NAME);
        $query->select('up_id')->andWhere(['up_version' => $version]);

        return (bool)$query->execute()->fetch('assoc');
    }
}
