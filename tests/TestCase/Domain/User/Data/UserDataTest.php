<?php

declare(strict_types = 1);

namespace App\Test\TestCase\Domain\User\Data;

use App\Domain\User\Data\UserData;
use App\Test\Fixture\UserFixture;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 */
class UserDataTest extends TestCase
{
    protected UserFixture $fixture;

    /**
     * Set up.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->fixture = new UserFixture();
    }

    /**
     * Test constructor.
     *
     * @return void
     */
    public function testConstruct(): void
    {
        $data = $this->fixture->records[0];
        $userdata = new UserData($data);
        $this->assertEquals($data['userid'], $userdata->userid);
        $this->assertEquals($data['name'], $userdata->name);
        $this->assertEquals($data['inactive'], $userdata->inactive);
        $this->assertEquals($data['super'], $userdata->super);
    }

    /**
     * Test transform.
     *
     * @return void
     */
    public function testTransform(): void
    {
        $data = $this->fixture->records[0];
        $userdata = new UserData($data);

        $array = $userdata->transform();
        $this->assertEquals($data['userid'], $array['userid']);
        $this->assertEquals($data['name'], $array['name']);
        $this->assertEquals($data['inactive'], $array['inactive']);
        $this->assertEquals($data['super'], $array['super']);
    }
}
