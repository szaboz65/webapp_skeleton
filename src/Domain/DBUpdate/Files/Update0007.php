<?php

namespace App\Domain\DBUpdate\Files;

use App\Domain\DBUpdate\Service\DBUpdateBase;

/**
 * Update file.
 */
class Update0007 extends DBUpdateBase
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(
            '0007',
            'Add Usersecret table',
            '2024-04-03 06:30:00'
        );
    }

    /**
     * {@inheritDoc}
     */
    public function doUpdate(): bool
    {
        $this->createTable();
        $count = $this->getTableRowCount('usersecret');
        if ($count == 0) {
            $this->insertRecords();
            $count = $this->getTableRowCount('usersecret');
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
            'CREATE TABLE IF NOT EXISTS `usersecret` (
              `userid` int(11) NOT NULL,
              `expire` DATETIME NOT NULL,
              `secret` varchar(255) DEFAULT NULL,
              `inactive` tinyint(1) DEFAULT 0,
              PRIMARY KEY (`userid`, `expire`),
              KEY `usec_ibfk_1` (`userid`),
              CONSTRAINT `usec_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;'
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
            "INSERT INTO `usersecret` (`userid`, `expire`, `secret`) VALUES 
                (1, '2024-01-01 00:00:00', '" . (string)password_hash('12345678', PASSWORD_DEFAULT) . "'),
                (1, '2025-01-01 00:00:00', '" . (string)password_hash('23456789', PASSWORD_DEFAULT) . "');"
        ));
    }
}
