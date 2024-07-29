<?php

namespace App\Domain\DBUpdate\Files;

use App\Domain\DBUpdate\Service\DBUpdateBase;

/**
 * Update file.
 */
class Update0009 extends DBUpdateBase
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(
            '0009',
            'Add Userfail table',
            '2024-04-03 13:30:00'
        );
    }

    /**
     * {@inheritDoc}
     */
    public function doUpdate(): bool
    {
        $this->createTable();
        $count = $this->getTableRowCount('userfail');
        if ($count == 0) {
            $this->insertRecords();
            $count = $this->getTableRowCount('userfail');
        }

        return 5 == $count;
    }

    /**
     * Create a table.
     *
     * @return void
     */
    private function createTable(): void
    {
        $this->execute($this->createQueryStatement(
            'CREATE TABLE IF NOT EXISTS `userfail` (
              `fail_userid` int(11) NOT NULL,
              `fail_occured` DATETIME NOT NULL,
              PRIMARY KEY (`fail_userid`, `fail_occured`),
              KEY `fail_ibfk_1` (`fail_userid`),
              CONSTRAINT `fail_ibfk_1` FOREIGN KEY (`fail_userid`) REFERENCES `user` (`userid`)
            ) ENGINE=InnoDB;'
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
            "INSERT INTO `userfail` (`fail_userid`, `fail_occured`) VALUES 
                (1, '2024-04-01 10:00:00'),
                (1, '2024-04-01 10:00:30'),
                (1, '2024-04-01 10:01:00'),
                (1, '2024-04-01 10:01:30'),
                (1, '2024-04-01 10:02:30');"
        ));
    }
}
