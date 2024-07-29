<?php

namespace App\Test\TestCase\Action\User;

use App\Test\Fixture\UserFixture;
use App\Test\Fixture\UserprefFixture;
use App\Test\Traits\AppTestTrait;
use App\Test\Traits\SessionTestTrait;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\DatabaseTestTrait;

/**
 * Test.
 *
 * @coversDefaultClass \App\Action\User\UserReadAction
 */
class UserReadActionTest extends TestCase
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
        $userfixture = new UserFixture();
        $userpreffixture = new UserprefFixture();
        $expected = array_merge($userfixture->records[0], $userpreffixture->records[0]);

        $request = $this->createRequest('GET', '/public/api/user/1');
        $this->withSession(1);
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $this->assertJsonData($expected, $response);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testInvalidId(): void
    {
        $request = $this->createRequest('GET', '/public/api/user/99');
        $this->withSession(1);
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_BAD_REQUEST, $response->getStatusCode());
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testReadUserWithoutLogin(): void
    {
        $request = $this->createRequest('GET', '/public/api/user/1');
        $this->withoutSession();
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_UNAUTHORIZED, $response->getStatusCode());
    }
}
