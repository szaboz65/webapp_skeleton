<?php

declare(strict_types = 1);

namespace App\Test\TestCase\Domain\User\Data;

use App\Domain\User\Data\RoleData;
use App\Test\Fixture\RoleFixture;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 */
class RoleDataTest extends TestCase
{
    protected RoleFixture $fixture;

    /**
     * Set up.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->fixture = new RoleFixture();
    }

    /**
     * Test constructor.
     *
     * @return void
     */
    public function testConstruct(): void
    {
        $data = $this->fixture->records[0];
        $roledata = new RoleData($data);
        $this->assertEquals($data['roleid'], $roledata->roleid);
        $this->assertEquals($data['rolename'], $roledata->rolename);
    }

    /**
     * Test transform.
     *
     * @return void
     */
    public function testTransform(): void
    {
        $data = $this->fixture->records[0];
        $roledata = new RoleData($data);

        $array = $roledata->transform();
        $this->assertEquals($data['roleid'], $array['roleid']);
        $this->assertEquals($data['rolename'], $array['rolename']);
    }

    /**
     * Test transform to item.
     *
     * @return void
     */
    public function testTransformItem(): void
    {
        $data = $this->fixture->records[0];
        $roledata = new RoleData($data);

        $array = $roledata->transformItem();
        $this->assertEquals($data['roleid'], $array['id']);
        $this->assertEquals($data['rolename'], $array['text']);
    }
}
