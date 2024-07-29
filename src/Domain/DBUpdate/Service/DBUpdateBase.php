<?php

declare(strict_types = 1);

namespace App\Domain\DBUpdate\Service;

use App\Domain\DBUpdate\Data\DBUpdateData;
use Cake\Chronos\Chronos;

/**
 * DB update file base.
 */
abstract class DBUpdateBase
{
    private DBUpdateData $data;

    private ?\PDO $pdo;

    /**
     * DB update file base.
     * Need set PDO!
     *
     * @param string $version The version string
     * @param string $description The description
     * @param string $releasedate The release date
     */
    public function __construct(string $version, string $description, string $releasedate)
    {
        $this->data = new DBUpdateData([
            'up_version' => $version,
            'up_description' => $description,
            'up_releasedate' => $releasedate,
            'up_updatedate' => (new Chronos())->toDateTimeString(),
        ]);
        $this->pdo = null;
    }

    /**
     * Do update function.
     */
    abstract public function doUpdate(): bool;

    /**
     * Fet the update info.
     *
     * @return DBUpdateData
     */
    public function getData(): DBUpdateData
    {
        return $this->data;
    }

    /**
     * Set the PDO.
     *
     * @param \PDO $pdo The PDO
     */
    public function setConnection(\PDO $pdo): void
    {
        $this->pdo = $pdo;
    }

    /**
     * Create query statement.
     *
     * @param string $sql The sql
     *
     * @throws \UnexpectedValueException
     *
     * @return \PDOStatement The statement
     */
    protected function createQueryStatement(string $sql): \PDOStatement
    {
        if (null === $this->pdo) {
            throw new \Exception('Missing Connection');
        }

        $statement = $this->pdo->query($sql, \PDO::FETCH_ASSOC);

        if (!$statement instanceof \PDOStatement) {
            throw new \UnexpectedValueException('Invalid SQL statement: ' . $sql);
        }

        return $statement;
    }

    /**
     * Execute the given statement.
     *
     * @param \PDOStatement $statement The statement
     *
     * @return bool
     */
    protected function execute(\PDOStatement $statement): bool
    {
        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Get table row count.
     *
     * @param string $table The table name
     *
     * @return int The number of rows
     */
    protected function getTableRowCount(string $table): int
    {
        $sql = sprintf('SELECT COUNT(*) AS counter FROM `%s`;', $table);
        $statement = $this->createQueryStatement($sql);
        $row = $statement->fetch(\PDO::FETCH_ASSOC) ?: [];

        return (int)($row['counter'] ?? 0);
    }
}
