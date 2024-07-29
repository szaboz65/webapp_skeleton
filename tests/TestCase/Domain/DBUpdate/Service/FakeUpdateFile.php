<?php

declare(strict_types = 1);

namespace App\Test\TestCase\Domain\DBUpdate\Service;

use App\Domain\DBUpdate\Service\DBUpdateBase;

class FakeUpdateFile extends DBUpdateBase
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(
            '1',
            'desc',
            '2024-03-29 15:00:00'
        );
    }

    /**
     * Do update function.
     */
    public function doUpdate(): bool
    {
        $this->dropTable();
        $this->createTable();
        $count = $this->getTableRowCount('test');
        if ($count == 0) {
            $this->insertRecords();
        }
        $result = $this->getTableRowCount('test');

        return 2 == $result;
    }

    /**
     * Create a test table.
     *
     * @return void
     */
    private function createTable(): void
    {
        $sql =
        'CREATE TABLE IF NOT EXISTS `test` (
          `roleid` int(11) NOT NULL AUTO_INCREMENT,
          `rolename` varchar(64) COLLATE utf8_hungarian_ci NOT NULL,
          PRIMARY KEY (`roleid`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci AUTO_INCREMENT=1;';
        $this->execute($this->createQueryStatement($sql));
    }

    /**
     * Drop the test table.
     *
     * @return void
     */
    private function dropTable(): void
    {
        $sql = 'DROP TABLE IF EXISTS `test`;';
        $this->execute($this->createQueryStatement($sql));
    }

    /**
     * insert same record into the test table.
     *
     * @return void
     */
    private function insertRecords(): void
    {
        $sql = "INSERT INTO `test` (`roleid`, `rolename`) VALUES
(1, 'Admin'),
(3, 'Accountant');";
        $this->execute($this->createQueryStatement($sql));
    }
}
