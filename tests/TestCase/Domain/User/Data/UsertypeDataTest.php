<?php

declare(strict_types = 1);

namespace App\Test\TestCase\Domain\User\Data;

use App\Domain\User\Data\UsertypeData;
use App\Test\Fixture\UsertypeFixture;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 */
class UsertypeDataTest extends TestCase
{
    protected UsertypeFixture $fixture;

    /**
     * Set up.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->fixture = new UsertypeFixture();
    }

    /**
     * Test constructor.
     *
     * @return void
     */
    public function testConstruct(): void
    {
        $data = $this->fixture->records[0];
        $usertypedata = new UsertypeData($data);
        $this->assertEquals($data['utypeid'], $usertypedata->utypeid);
        $this->assertEquals($data['utypename'], $usertypedata->utypename);
    }

    /**
     * Test transform.
     *
     * @return void
     */
    public function testTransform(): void
    {
        $data = $this->fixture->records[0];
        $usertypedata = new UsertypeData($data);

        $array = $usertypedata->transform();
        $this->assertEquals($data['utypeid'], $array['utypeid']);
        $this->assertEquals($data['utypename'], $array['utypename']);
    }

    /**
     * Test transform to item.
     *
     * @return void
     */
    public function testTransformItem(): void
    {
        $data = $this->fixture->records[0];
        $usertypedata = new UsertypeData($data);

        $array = $usertypedata->transformItem();
        $this->assertEquals($data['utypeid'], $array['id']);
        $this->assertEquals($data['utypename'], $array['text']);
    }
}
