<?php

declare(strict_types = 1);

namespace App\Test\TestCase\Domain\User\Data;

use App\Domain\User\Data\UserFindData;
use App\Test\Fixture\UserFixture;
use App\Test\Fixture\UsertypeFixture;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 */
class UserFindDataTest extends TestCase
{
    protected UserFixture $userdatafixture;
    protected UsertypeFixture $usertypefixture;

    /**
     * Set up.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->userdatafixture = new UserFixture();
        $this->usertypefixture = new UsertypeFixture();
    }

    /**
     * Test constructor.
     *
     * @return void
     */
    public function testConstruct(): void
    {
        $data = array_merge($this->userdatafixture->records[0], $this->usertypefixture->records[0]);
        $object = new UserFindData($data);
        $this->assertEquals($data['userid'], $object->userData->userid);
        $this->assertEquals($data['name'], $object->userData->name);
        $this->assertEquals($data['utypeid'], $object->usertypeData->utypeid);
        $this->assertEquals($data['utypename'], $object->usertypeData->utypename);
    }

    /**
     * Test transform.
     *
     * @return void
     */
    public function testTransform(): void
    {
        $data = array_merge($this->userdatafixture->records[0], $this->usertypefixture->records[0]);
        $object = new UserFindData($data);

        $array = $object->transform();
        $this->assertEquals($data['userid'], $array['userid']);
        $this->assertEquals($data['name'], $array['name']);
        $this->assertEquals($data['utypeid'], $array['utypeid']);
        $this->assertEquals($data['utypename'], $array['utypename']);
    }
}
