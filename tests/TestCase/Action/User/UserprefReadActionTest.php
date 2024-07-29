<?php

namespace App\Test\TestCase\Action\User;

use App\Test\Fixture\UserprefFixture;
use App\Test\Traits\AppTestTrait;
use App\Test\Traits\SessionTestTrait;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\DatabaseTestTrait;

/**
 * Test.
 *
 * @coversDefaultClass \App\Action\User\UserprefReadAction
 */
class UserprefReadActionTest extends TestCase
{
    use AppTestTrait;
    use DatabaseTestTrait;
    use SessionTestTrait;

    /**
     * Test.
     *
     * @return void
     */
    public function testValidId(): void
    {
        $request = $this->createRequest('GET', '/public/api/user/1/pref');
        $this->withSession(1);
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $this->assertJsonData((new UserprefFixture())->records[0], $response);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testInvalidId(): void
    {
        $request = $this->createRequest('GET', '/public/api/user/99/pref');
        $this->withSession(1);
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testReadUserprefWithoutLogin(): void
    {
        $request = $this->createRequest('GET', '/public/api/user/1/pref');
        $this->withoutSession();
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_UNAUTHORIZED, $response->getStatusCode());
    }
}
