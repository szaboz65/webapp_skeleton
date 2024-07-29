<?php

declare(strict_types = 1);

namespace App\Test\TestCase\Domain\User\Data;

use App\Domain\User\Data\UsersessionData;
use Cake\Chronos\Chronos;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 */
class UsersessionDataTest extends TestCase
{
    protected array $fixture;

    /**
     * Set up.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->fixture = [
            'ses_userid' => 1,
            'ses_lastlogin' => '2024-04-03 10:00:00',
            'ses_lastactive' => '2024-04-03 10:00:00',
            'ses_expire' => '2024-04-03 11:00:00',
        ];
    }

    /**
     * Test constructor.
     *
     * @return void
     */
    public function testConstruct(): void
    {
        Chronos::setTestNow('2024-04-03 10:00:00');
        $usersession = new UsersessionData(['ses_userid' => 1]);
        Chronos::setTestNow();
        $this->assertEquals($this->fixture['ses_userid'], $usersession->userid);
    }

    /**
     * Test constructor.
     *
     * @return void
     */
    public function testConstructWithData(): void
    {
        $usersession = new UsersessionData($this->fixture);
        $this->assertEquals($this->fixture['ses_userid'], $usersession->userid);
    }

    /**
     * Test transform.
     *
     * @return void
     */
    public function testTransform(): void
    {
        $usersession = new UsersessionData($this->fixture);

        $array = $usersession->transform();
        $this->assertEquals($this->fixture['ses_userid'], $array['ses_userid']);
        $this->assertEquals($this->fixture['ses_lastlogin'], $array['ses_lastlogin']);
        $this->assertEquals($this->fixture['ses_lastactive'], $array['ses_lastactive']);
        $this->assertEquals($this->fixture['ses_expire'], $array['ses_expire']);
    }

    /**
     * Test isExpired.
     *
     * @return void
     */
    public function testisExpire(): void
    {
        $usersession = new UsersessionData($this->fixture);
        $this->assertTrue($usersession->isExpired());
        $this->assertFalse($usersession->isExpired('2024-04-03 10:23:34'));
    }
}
