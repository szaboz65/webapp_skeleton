<?php

namespace App\Domain\DBUpdate\Files;

use App\Domain\DBUpdate\Service\DBUpdateBase;

/**
 * Update file.
 */
class Update0004 extends DBUpdateBase
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(
            '0004',
            'Add User table',
            '2024-04-01 17:30:00'
        );
    }

    /**
     * {@inheritDoc}
     */
    public function doUpdate(): bool
    {
        $this->createTable();
        $count = $this->getTableRowCount('user');
        if ($count == 0) {
            $this->insertRecords();
            $count = $this->getTableRowCount('user');
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
            'CREATE TABLE IF NOT EXISTS `user` (
              `userid` int(11) NOT NULL AUTO_INCREMENT,
              `fk_utypeid` int(11) NOT NULL,
              `name` varchar(64) COLLATE utf8_hungarian_ci NOT NULL,
              `phone` varchar(32) COLLATE utf8_hungarian_ci NOT NULL,
              `title` varchar(32) COLLATE utf8_hungarian_ci NOT NULL,
              `email` varchar(128) COLLATE utf8_hungarian_ci NOT NULL,
              `inactive` tinyint(1) DEFAULT 0,
              `super` tinyint(1) DEFAULT 0,
              PRIMARY KEY (`userid`),
              KEY `user_ibfk_1` (`fk_utypeid`),
              CONSTRAINT `user_ibfk_1` FOREIGN KEY (`fk_utypeid`) REFERENCES `usertype` (`utypeid`)
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
            "INSERT INTO `user` (`userid`, `fk_utypeid`, `name`, `phone`, `title`, `email`, `inactive`, `super`) 
                VALUES
            (1, 1, 'Admin User', '+123456789012', 'Admin', 'zoltan.szabo65@gmail.com', 0, 1),
            (2, 2, 'Other User', '+123456789012', 'Other', 'other.user@mail.com', 0, 0);"
        ));
    }
}
