<?php

namespace App\Domain\DBUpdate\Files;

use App\Domain\DBUpdate\Service\DBUpdateBase;

/**
 * Update file.
 */
class Update0003 extends DBUpdateBase
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(
            '0003',
            'Add Usertype table',
            '2024-03-30 06:30:00'
        );
    }

    /**
     * {@inheritDoc}
     */
    public function doUpdate(): bool
    {
        $this->createTable();
        $count = $this->getTableRowCount('usertype');
        if ($count == 0) {
            $this->insertRecords();
            $count = $this->getTableRowCount('usertype');
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
            'CREATE TABLE IF NOT EXISTS `usertype` (
              `utypeid` int(11) NOT NULL  AUTO_INCREMENT,
              `utypename` varchar(64) COLLATE utf8_hungarian_ci NOT NULL,
              `roles` int(11) DEFAULT 0,
              PRIMARY KEY (`utypeid`)
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
            "INSERT INTO `usertype` (`utypeid`, `utypename`, `roles`) VALUES 
(1, 'Admin', 3),
(2, 'Other', 2);"
        ));
    }
}
