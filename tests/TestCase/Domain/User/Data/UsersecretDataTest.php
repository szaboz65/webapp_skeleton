<?php

declare(strict_types = 1);

namespace App\Test\TestCase\Domain\User\Data;

use App\Domain\User\Data\UsersecretData;
use App\Test\Fixture\UsersecretFixture;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 */
class UsersecretDataTest extends TestCase
{
    protected UsersecretFixture $fixture;

    /**
     * Set up.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->fixture = new UsersecretFixture();
    }

    /**
     * Test constructor.
     *
     * @return void
     */
    public function testConstruct(): void
    {
        $data = $this->fixture->records[0];
        $usersecret = new UsersecretData($data);
        $this->assertEquals($data['userid'], $usersecret->userid);
    }

    /**
     * Test transform.
     *
     * @return void
     */
    public function testTransform(): void
    {
        $data = $this->fixture->records[0];
        $usersecret = new UsersecretData($data);

        $array = $usersecret->transform();
        $this->assertEquals($data['userid'], $array['userid']);
        $this->assertEquals($data['expire'], $array['expire']);
        $this->assertEquals($data['secret'], $array['secret']);
    }

    /**
     * Test setExpire.
     *
     * @return void
     */
    public function testsetExpire(): void
    {
        $data = $this->fixture->records[0];
        $usersecret = new UsersecretData($data);

        $expire = '2024-04-03 00:00:00';
        $usersecret->setExpire($expire);

        $array = $usersecret->transform();
        $this->assertEquals($expire, $array['expire']);
    }

    /**
     * Test setSecret.
     *
     * @return void
     */
    public function testsetSecret(): void
    {
        $data = $this->fixture->records[0];
        $usersecret = new UsersecretData($data);

        $secret = '23456789';
        $usersecret->setSecret($secret);

        $array = $usersecret->transform();
        $this->assertEquals('$2y$10$', substr($array['secret'], 0, 7));
    }

    /**
     * Test verifySecret.
     *
     * @return void
     */
    public function testverifySecret(): void
    {
        $data = $this->fixture->records[0];
        $usersecret = new UsersecretData($data);

        $this->assertFalse($usersecret->verifySecret('123456'));
        $this->assertTrue($usersecret->verifySecret('azAZ09!?'));
    }
}
