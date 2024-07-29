<?php

namespace App\Test\TestCase\Action\Session;

use App\Test\Traits\AppTestTrait;
use App\Test\Traits\SessionTestTrait;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\DatabaseTestTrait;

/**
 * Test.
 */
class SessionReadActionTest extends TestCase
{
    use AppTestTrait;
    use DatabaseTestTrait;
    use SessionTestTrait;

    /**
     * Test.
     *
     * @return void
     */
    public function testSessionRead(): void
    {
        $request = $this->createRequest('GET', '/public/api/session');
        $this->withSession(1);
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $data = $this->getJsonData($response);
        $this->assertArrayHasKey('user', $data);
        $this->assertArrayHasKey('pref', $data);
        $this->assertArrayHasKey('type', $data);
        $this->assertArrayHasKey('session', $data);
        $this->assertArrayHasKey('roles', $data);
        $this->assertEquals(2, count($data['roles']));
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testSessionReadOneRole(): void
    {
        $request = $this->createRequest('GET', '/public/api/session');
        $this->withSession(2);
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $data = $this->getJsonData($response);
        $this->assertEquals(1, count($data['roles']));
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testSessionReadWithoutLogin(): void
    {
        $request = $this->createRequest('GET', '/public/api/session');
        $this->withoutSession();
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_UNAUTHORIZED, $response->getStatusCode());
    }
}
