<?php

namespace App\Domain\DBUpdate\Files;

use App\Domain\DBUpdate\Service\DBUpdateBase;

/**
 * Update file.
 */
class Update0008 extends DBUpdateBase
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(
            '0008',
            'Add Usersession table',
            '2024-04-03 10:30:00'
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
            'CREATE TABLE IF NOT EXISTS `usersession` (
                `ses_userid` INT(11) NOT NULL,
                `ses_lastlogin` DATETIME NOT NULL,
                `ses_lastactive` DATETIME NOT NULL,
                `ses_expire` DATETIME NOT NULL,
                CONSTRAINT `ses_ibfk_1` FOREIGN KEY (`ses_userid`) REFERENCES `user` (`userid`)
                ) ENGINE = InnoDB;'
        ));
    }
}
