<?php

declare(strict_types = 1);

namespace App\Test\TestCase\Domain\DBUpdate\Service\DBUpdater;

use App\Support\FileCollector;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 */
class FileCollectorTest extends TestCase
{
    protected FileCollector $object;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->object = new FileCollector();
    }

    /**
     * Test.
     */
    public function testAddGet(): void
    {
        $this->assertEquals(0, count($this->object->get()));
        $this->object->add('qwerty');
        $this->assertEquals(1, count($this->object->get()));
        $this->assertEquals('qwerty', $this->object->get()[0]);
    }

    /**
     * Test.
     */
    public function testCollectFiles(): void
    {
        $directory = dirname(dirname(dirname(__DIR__))) . '/src/Domain/DBUpdate/Files';
        $filemask = '/^Update\d{4}.php$/';
        $result = $this->object->collectFiles($directory, $filemask);
        $this->assertTrue(0 < count($result));
        $this->assertEquals('Update0001.php', $result[0]);
    }
}
