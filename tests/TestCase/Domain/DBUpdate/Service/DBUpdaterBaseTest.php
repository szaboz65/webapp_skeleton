<?php

declare(strict_types = 1);

namespace App\Test\TestCase\Domain\DBUpdate\Service;

use App\Test\Traits\AppTestTrait;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 */
class DBUpdaterBaseTest extends TestCase
{
    use AppTestTrait;

    /**
     * Test.
     *
     * @return void
     */
    public function test(): void
    {
        $pdo = $this->container->get(\PDO::class);
        $object = new FakeUpdateFile();
        $object->setConnection($pdo);
        $this->assertTrue($object->doUpdate());
    }
}
