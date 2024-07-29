<?php

declare(strict_types = 1);

namespace App\Support\W2;

use App\Factory\QueryFactory;

/**
 * Basic repository operations.
 */
class RepositoryBase
{
    private QueryFactory $queryFactory;

    private bool $exception;

    /**
     * Constructor.
     *
     * @param QueryFactory $queryFactory The query factory
     */
    public function __construct(QueryFactory $queryFactory)
    {
        $this->queryFactory = $queryFactory;
        $this->exception = true;
    }

    /**
     * Get query factory.
     *
     * @return QueryFactory
     */
    public function getQueryFactory(): QueryFactory
    {
        return $this->queryFactory;
    }

    /**
     * Set/clear to handle exception.
     *
     * @param bool $exception Set/Clear flag
     *
     * @return self
     */
    public function setException(bool $exception): self
    {
        $this->exception = $exception;

        return $this;
    }

    /**
     * Get a record.
     *
     * @param string $table The table name
     * @param string $key The table key
     * @param string $id The record id
     * @param array $fields The fields
     *
     * @throws \DomainException
     *
     * @return array
     */
    public function getRecord(string $table, string $key, string $id, array $fields = null): array
    {
        $query = $this->queryFactory->newSelect($table)
            ->select($fields ?? '*')
            ->andWhere([$key => $id])
            ->limit(1);

        $row = $query->execute()->fetch('assoc');

        if ($this->exception && !$row) {
            throw new \DomainException(sprintf('Record not found: %s.%s:%s', $table, $key, $id));
        }

        return $row;
    }

    /**
     * Insert a new record.
     *
     * @param string $table The table name
     * @param array $record The key field
     *
     * @return int The id of the last inserted record
     */
    public function insertRecord(string $table, array $record): int
    {
        return (int)$this->queryFactory->newInsert($table, $record)
            ->execute()
            ->lastInsertId();
    }

    /**
     * Update the given record.
     *
     * @param string $table The table name
     * @param string $key The key field
     * @param string $id The record id
     * @param array $record The record data
     *
     * @return void
     */
    public function updateRecord(string $table, string $key, string $id, array $record): void
    {
        $this->queryFactory->newUpdate($table, $record)
            ->andWhere([$key => $id])
            ->execute();
    }

    /**
     * Is a record exists?
     *
     * @param string $table The table name
     * @param string $key The key field
     * @param string $id The record id
     *
     * @return bool True if it exists
     */
    public function existsRecord(string $table, string $key, string $id): bool
    {
        $query = $this->queryFactory->newSelect($table)
            ->select($key)->andWhere([$key => $id]);

        return (bool)$query->execute()->fetch('assoc');
    }

    /**
     * Delete the given record.
     *
     * @param string $table The table name
     * @param string $key The key field
     * @param string $id The record id
     *
     * @return void
     */
    public function deleteRecord(string $table, string $key, string $id): void
    {
        $this->queryFactory->newDelete($table)
            ->andWhere([$key => $id])
            ->execute();
    }
}
