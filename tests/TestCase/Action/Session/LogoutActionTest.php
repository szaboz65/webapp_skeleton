<?php

namespace App\Test\TestCase\Action\Session;

use App\Test\Traits\AppTestTrait;
use App\Test\Traits\DBTestTrait;
use App\Test\Traits\SessionTestTrait;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\DatabaseTestTrait;

/**
 * Test.
 */
class LogoutActionTest extends TestCase
{
    use AppTestTrait;
    use DatabaseTestTrait;
    use DBTestTrait;
    use SessionTestTrait;

    /**
     * Test.
     *
     * @return void
     */
    public function testLogout(): void
    {
        $request = $this->createJsonRequest('GET', '/public/api/logout');
        $this->withSession(1);
        $cntBefore = $this->getTableRowCount('usersession');
        $response = $this->app->handle($request);
        $cntAfter = $this->getTableRowCount('usersession');

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $data = $this->getJsonData($response);
        $this->assertArrayHasKey('error', $data);
        $this->assertFalse($data['error']);
    }
}
