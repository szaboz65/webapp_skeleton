<?php

declare(strict_types = 1);

namespace App\Test\TestCase\Domain\User\Repository;

use App\Domain\User\Data\UsersecretData;
use App\Domain\User\Repository\UsersecretFinderRepository;
use App\Factory\QueryFactory;
use App\Support\Hydrator;
use App\Test\Fixture\UsersecretFixture;
use App\Test\Traits\AppTestTrait;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 */
class UsersecretFinderRepositoryTest extends TestCase
{
    use AppTestTrait;

    protected UsersecretFinderRepository $repo;

    /**
     * Set up.
     *
     * @return void
     */
    private function createRepo(): void
    {
        $this->repo = new UsersecretFinderRepository($this->container->get(QueryFactory::class), new Hydrator());
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testFindUsersecretFoundNow(): void
    {
        $this->createRepo();
        $expected = new UsersecretData((new UsersecretFixture())->records[1]);
        $actual = $this->repo->findUsersecret((int)$expected->userid);
        $this->assertEquals([$expected], $actual);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testFindUsersecretNotExistUser(): void
    {
        $this->createRepo();
        $actual = $this->repo->findUsersecret(99);
        $this->assertEquals([], $actual);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testFindUsersecretExpired(): void
    {
        $this->createRepo();
        $actual = $this->repo->findUsersecret(1, '2026-01-01 00:00:00');
        $this->assertEquals([], $actual);
    }
}
