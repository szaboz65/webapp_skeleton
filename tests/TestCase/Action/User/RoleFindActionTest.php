<?php

declare(strict_types = 1);

namespace App\Test\TestCase\Action\User;

use App\Test\Fixture\RoleFixture;
use App\Test\Traits\AppTestTrait;
use App\Test\Traits\SessionTestTrait;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\DatabaseTestTrait;

/**
 * Test.
 *
 * @coversDefaultClass \App\Action\User\RoleFindAction
 */
class RoleFindActionTest extends TestCase
{
    use AppTestTrait;
    use DatabaseTestTrait;
    use SessionTestTrait;

    /**
     * Test.
     *
     * @return void
     */
    public function testListRoles(): void
    {
        $request = $this->createRequest('GET', '/public/api/roles');
        $this->withSession(1);
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonData((new RoleFixture())->records, $response);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testListRolesWithoutLogin(): void
    {
        $request = $this->createRequest('GET', '/public/api/roles');
        $this->withoutSession();
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_UNAUTHORIZED, $response->getStatusCode());
    }
}
