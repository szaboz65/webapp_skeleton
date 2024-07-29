<?php

declare(strict_types = 1);

namespace App\Test\TestCase\Domain\DBUpdate\Repository;

use App\Domain\DBUpdate\Data\DBUpdateData;
use App\Domain\DBUpdate\Repository\DBUpdateRepository;
use App\Factory\QueryFactory;
use App\Test\Fixture\DBUpdateFixture;
use App\Test\Traits\AppTestTrait;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 */
class DBUpdateRepositoryTest extends TestCase
{
    use AppTestTrait;

    protected DBUpdateRepository $repo;

    /**
     * Set up.
     *
     * @return void
     */
    private function createRepo(): void
    {
        $this->repo = new DBUpdateRepository($this->container->get(QueryFactory::class));
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testExists(): void
    {
        $this->createRepo();
        // not exists
        $this->assertFalse($this->repo->existsVersion('asdf'));
        // $this->assertTrue($this->table->exists());
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testInsert(): void
    {
        $this->createRepo();
        $dbupdate = new DBUpdateData((new DBUpdateFixture())->record);
        if ($this->repo->existsVersion($dbupdate->version ?? '0001')) {
            // exists
            $this->assertTrue(true);
        } else {
            // not exist
            $id = $this->repo->insert($dbupdate);
            $this->assertEquals(1, $id);
            $this->assertTrue($this->repo->existsVersion($dbupdate->version ?? '0001'));
        }
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testGetById(): void
    {
        $this->createRepo();
        $expected = new DBUpdateData((new DBUpdateFixture())->record);
        $actual = $this->repo->getById($expected->id ?? 1);
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testGetByIdNotExist(): void
    {
        $this->createRepo();
        $this->expectException(\DomainException::class);
        $this->repo->getById(123456);
    }
}
