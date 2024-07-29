<?php

namespace App\Domain\DBUpdate\Files;

use App\Domain\DBUpdate\Service\DBUpdateBase;

/**
 * Update file.
 */
class Update0002 extends DBUpdateBase
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(
            '0002',
            'Add Role table',
            '2024-03-29 15:00:00'
        );
    }

    /**
     * {@inheritDoc}
     */
    public function doUpdate(): bool
    {
        $this->createTable();
        $count = $this->getTableRowCount('role');
        if ($count == 0) {
            $this->insertRecords();
            $count = $this->getTableRowCount('role');
        }

        return 2 == $count;
    }

    /**
     * Create a table.
     *
     * @return void
     */
    private function createTable(): void
    {
        $this->execute($this->createQueryStatement(
            'CREATE TABLE IF NOT EXISTS `role` (
              `roleid` int(11) NOT NULL AUTO_INCREMENT,
              `rolename` varchar(64) COLLATE utf8_hungarian_ci NOT NULL,
              PRIMARY KEY (`roleid`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=1;'
        ));
    }

    /**
     * insert same record into the test table.
     *
     * @return void
     */
    private function insertRecords(): void
    {
        $this->execute($this->createQueryStatement(
            "INSERT INTO `role` (`roleid`, `rolename`) VALUES
(1, 'Admin'),
(2, 'Other');"
        ));
    }
}
