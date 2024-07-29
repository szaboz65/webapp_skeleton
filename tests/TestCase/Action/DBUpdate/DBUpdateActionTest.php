<?php

namespace App\Test\TestCase\Action\DBUpdate;

use App\Test\Traits\AppTestTrait;
use App\Test\Traits\SessionTestTrait;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\DatabaseTestTrait;

/**
 * Test.
 */
class DBUpdateActionTest extends TestCase
{
    use AppTestTrait;
    use DatabaseTestTrait;
    use SessionTestTrait;

    /**
     * Test.
     *
     * @return void
     */
    public function testUpdate(): void
    {
        $request = $this->createRequest('GET', '/public/api/dbupdate');
        $this->withSession(1);
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $data = $this->getJsonData($response);
        $this->assertArrayHasKey('files', $data);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testUpdateWithoutLogin(): void
    {
        $request = $this->createRequest('GET', '/public/api/dbupdate');
        $this->withoutSession();
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
    }
}
