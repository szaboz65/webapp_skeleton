<?php

declare(strict_types = 1);

namespace App\Test\TestCase\Domain\DBUpdate\Repository;

use App\Domain\DBUpdate\Repository\DBUpdateTable;
use App\Test\Traits\AppTestTrait;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 */
class DBUpdateTableTest extends TestCase
{
    use AppTestTrait;

    protected DBUpdateTable $table;

    /**
     * Set up.
     *
     * @return void
     */
    private function createTable(): void
    {
        $this->table = new DBUpdateTable($this->container->get(\PDO::class));
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testExists(): void
    {
        $this->createTable();
        // not exists        $this->assertFalse($this->table->exists());
        $this->assertTrue($this->table->exists());
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testCreate(): void
    {
        $this->createTable();
        $this->table->createTable();
        $this->assertTrue($this->table->exists());
    }
}
