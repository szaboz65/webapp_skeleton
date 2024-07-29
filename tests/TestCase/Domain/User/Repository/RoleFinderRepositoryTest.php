<?php

declare(strict_types = 1);

namespace App\Test\TestCase\Domain\User\Repository;

use App\Domain\User\Data\RoleData;
use App\Domain\User\Repository\RoleFinderRepository;
use App\Factory\QueryFactory;
use App\Support\BinArrayConverter;
use App\Support\Hydrator;
use App\Test\Fixture\RoleFixture;
use App\Test\Traits\AppTestTrait;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 */
class RoleFinderRepositoryTest extends TestCase
{
    use AppTestTrait;

    protected RoleFinderRepository $repo;

    /**
     * Set up.
     *
     * @return void
     */
    private function createRepo(): void
    {
        $this->repo = new RoleFinderRepository($this->container->get(QueryFactory::class), new Hydrator());
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testFindRoles(): void
    {
        $this->createRepo();
        $expected = [
            new RoleData((new RoleFixture())->records[0]),
            new RoleData((new RoleFixture())->records[1]),
        ];
        $actual = $this->repo->findRoles();
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testFindRolesAll(): void
    {
        $this->createRepo();
        $expected = [
            new RoleData((new RoleFixture())->records[0]),
            new RoleData((new RoleFixture())->records[1]),
        ];
        $actual = $this->repo->findRoles(BinArrayConverter::makeArrayFromBin(3));
        $this->assertEquals($expected, $actual);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testFindRole(): void
    {
        $this->createRepo();
        $expected = [
            new RoleData((new RoleFixture())->records[0]),
        ];
        $actual = $this->repo->findRoles(BinArrayConverter::makeArrayFromBin(1));
        $this->assertEquals($expected, $actual);
    }
}
