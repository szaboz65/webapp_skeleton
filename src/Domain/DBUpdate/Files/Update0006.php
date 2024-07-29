<?php

namespace App\Domain\DBUpdate\Files;

use App\Domain\DBUpdate\Service\DBUpdateBase;

/**
 * Update file.
 */
class Update0006 extends DBUpdateBase
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(
            '0006',
            'Add Userphoto table',
            '2024-04-02 17:00:00'
        );
    }

    /**
     * {@inheritDoc}
     */
    public function doUpdate(): bool
    {
        $this->createTable();
        $count = $this->getTableRowCount('photo');
        if ($count == 0) {
            $this->insertRecords();
            $count = $this->getTableRowCount('photo');
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
            'CREATE TABLE IF NOT EXISTS `photo` (
              `userid` int(11) NOT NULL,
              `photo` MEDIUMTEXT,
              PRIMARY KEY (`userid`)
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
            // phpcs:ignore Generic.Files.LineLength
            "INSERT INTO `photo` (`userid`, `photo`) VALUES (1, 'data:image/jpeg;base64,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');"
        ));
    }
}
