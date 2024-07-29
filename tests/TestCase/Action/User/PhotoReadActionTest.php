<?php

namespace App\Test\TestCase\Action\User;

use App\Test\Fixture\UserphotoFixture;
use App\Test\Traits\AppTestTrait;
use App\Test\Traits\SessionTestTrait;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\DatabaseTestTrait;

/**
 * Test.
 *
 * @coversDefaultClass \App\Action\User\PhotoReadAction
 */
class PhotoReadActionTest extends TestCase
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
        $request = $this->createRequest('GET', '/public/api/user/1/photo');
        $this->withSession(1);
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertEquals((new UserphotoFixture())->records[0]['photo'], $response->getBody());
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testInvalidId(): void
    {
        $request = $this->createRequest('GET', '/public/api/user/99/photo');
        $this->withSession(1);
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testReadPhotoWithoutLogin(): void
    {
        $request = $this->createRequest('GET', '/public/api/user/1/photo');
        $this->withoutSession();
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_UNAUTHORIZED, $response->getStatusCode());
    }
}
