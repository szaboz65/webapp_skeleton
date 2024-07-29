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
 * @coversDefaultClass \App\Action\User\RoleItemsAction
 */
class UserRoleitemsActionTest extends TestCase
{
    use AppTestTrait;
    use DatabaseTestTrait;
    use SessionTestTrait;

    /**
     * Test.
     *
     * @return void
     */
    public function testUserRoleItems(): void
    {
        $request = $this->createRequest('GET', '/public/api/user/1/roleitems');
        $this->withSession(1);
        $response = $this->app->handle($request);

        $fixtures = (new RoleFixture())->records;
        $expected = [
            [
                'id' => $fixtures[0]['roleid'],
                'text' => $fixtures[0]['rolename'],
            ],
            [
                'id' => $fixtures[1]['roleid'],
                'text' => $fixtures[1]['rolename'],
            ],
        ];
        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonData($expected, $response);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testRoleItemsFiltered(): void
    {
        $request = $this->createRequest('GET', '/public/api/user/2/roleitems');
        $this->withSession(1);
        $response = $this->app->handle($request);

        $fixtures = (new RoleFixture())->records;
        $expected = [
            [
                'id' => $fixtures[1]['roleid'],
                'text' => $fixtures[1]['rolename'],
            ],
        ];
        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonData($expected, $response);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testRoleItemsWithoutLogin(): void
    {
        $request = $this->createRequest('GET', '/public/api/user/1/roleitems');
        $this->withoutSession();
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_UNAUTHORIZED, $response->getStatusCode());
    }
}
