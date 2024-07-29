<?php

namespace App\Domain\DBUpdate\Files;

use App\Domain\DBUpdate\Service\DBUpdateBase;

/**
 * Update file.
 */
class Update0005 extends DBUpdateBase
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(
            '0005',
            'Add Userpref table',
            '2024-04-01 22:30:00'
        );
    }

    /**
     * {@inheritDoc}
     */
    public function doUpdate(): bool
    {
        $this->createTable();
        $count = $this->getTableRowCount('userpref');
        if ($count == 0) {
            $this->insertRecords();
            $count = $this->getTableRowCount('userpref');
        }

        return 1 == $count;
    }

    /**
     * Create a table.
     *
     * @return void
     */
    private function createTable(): void
    {
        $this->execute($this->createQueryStatement(
            "CREATE TABLE IF NOT EXISTS `userpref` (
              `upref_id` int(11) DEFAULT NULL,
              `locale` char(32) DEFAULT 'en-US',
              `schema` char(32) DEFAULT 'normal',
              PRIMARY KEY (`upref_id`),
              CONSTRAINT `upref_ibfk_1` FOREIGN KEY (`upref_id`) REFERENCES `user` (`userid`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;"
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
            'INSERT INTO `userpref` (`upref_id`) VALUES 
            (1),
            (2);'
        ));
    }
}
