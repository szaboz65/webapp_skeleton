<?php

declare(strict_types = 1);

namespace App\Test\TestCase\Domain\DBUpdate\Data;

use App\Domain\DBUpdate\Data\DBUpdateData;
use App\Test\Fixture\DBUpdateFixture;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 */
class DBUpdateDataTest extends TestCase
{
    protected DBUpdateFixture $fixture;

    /**
     * Set up.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->fixture = new DBUpdateFixture();
    }

    /**
     * Test constructor.
     *
     * @return void
     */
    public function testConstruct(): void
    {
        $data = new DBUpdateData($this->fixture->record);
        $this->assertEquals($this->fixture->record['up_id'], $data->id);
        $this->assertEquals($this->fixture->record['up_version'], $data->version);
        $this->assertEquals($this->fixture->record['up_description'], $data->description);
    }

    /**
     * Test transform.
     *
     * @return void
     */
    public function testTransform(): void
    {
        $data = new DBUpdateData($this->fixture->record);

        $array = $data->transform();
        $this->assertEquals($this->fixture->record['up_id'], $array['up_id']);
        $this->assertEquals($this->fixture->record['up_version'], $array['up_version']);
        $this->assertEquals($this->fixture->record['up_description'], $array['up_description']);
    }
}
