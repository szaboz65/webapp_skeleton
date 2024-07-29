<?php

namespace App\Domain\DBUpdate\Files;

use App\Domain\DBUpdate\Service\DBUpdateBase;

/**
 * Update file.
 */
class Update0010 extends DBUpdateBase
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(
            '0010',
            'Add Userpassreset table',
            '2024-04-03 14:30:00'
        );
    }

    /**
     * {@inheritDoc}
     */
    public function doUpdate(): bool
    {
        $this->createTable();

        return true;
    }

    /**
     * Create a table.
     *
     * @return void
     */
    private function createTable(): void
    {
        $this->execute($this->createQueryStatement(
            'CREATE TABLE IF NOT EXISTS `pass_reset` (
              `userid` int(11) DEFAULT NULL,
              `reset_code` char(64) DEFAULT NULL,
              `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
              `expire` DATETIME NOT NULL DEFAULT TIMESTAMPADD(DAY,2,CURRENT_TIMESTAMP),
              KEY `pw_ibfk_1` (`userid`),
              CONSTRAINT `pw_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `user` (`userid`)
            ) ENGINE=InnoDB;'
        ));
    }
}
