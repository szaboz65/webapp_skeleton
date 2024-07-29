<?php

declare(strict_types = 1);

namespace App\Domain\DBUpdate\Repository;

/**
 * Repository.
 */
class DBUpdateTable
{
    private \PDO $pdo;

    /**
     * The constructor.
     *
     * @param \PDO $pdo The pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Check if the table exists.
     *
     * @return bool if exists
     */
    public function exists(): bool
    {
        $statement = $this->query(
            'SELECT table_name
                FROM information_schema.tables
                WHERE table_schema = database() AND table_name = "dbupdate";'
        );

        $rows = (array)$statement->fetchAll(\PDO::FETCH_ASSOC);

        return 1 == count($rows);
    }

    /**
     * Create table if the table does not exist.
     */
    public function createTable(): void
    {
        $statement = $this->query(
            'CREATE TABLE IF NOT EXISTS `dbupdate` (
              `up_id` int(11) NOT NULL AUTO_INCREMENT,
              `up_version` varchar(16) COLLATE utf8_hungarian_ci NOT NULL,
              `up_description` varchar(64) COLLATE utf8_hungarian_ci NOT NULL,
              `up_releasedate` DATETIME DEFAULT NULL,
              `up_updatedate` DATETIME DEFAULT NULL,
              PRIMARY KEY (`up_id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=1;'
        );

        $statement->fetchAll(\PDO::FETCH_ASSOC);
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
    private function query(string $sql): \PDOStatement
    {
        $statement = $this->pdo->query($sql);

        if (!$statement) {
            throw new \UnexpectedValueException('Query failed');
        }

        return $statement;
    }
}
