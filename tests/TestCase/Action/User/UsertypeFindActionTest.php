<?php

declare(strict_types = 1);

namespace App\Test\TestCase\Action\User;

use App\Test\Fixture\RoleFixture;
use App\Test\Fixture\UsertypeFixture;
use App\Test\Traits\AppTestTrait;
use App\Test\Traits\SessionTestTrait;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\DatabaseTestTrait;

/**
 * Test.
 *
 * @coversDefaultClass \App\Action\User\UsertypeFindAction
 */
class UsertypeFindActionTest extends TestCase
{
    use AppTestTrait;
    use DatabaseTestTrait;
    use SessionTestTrait;

    /**
     * Test.
     *
     * @return void
     */
    public function testListUsertypes(): void
    {
        $usertypes = (new UsertypeFixture())->records;
        $roles = (new RoleFixture())->records;
        $usertypes[0]['role'] = $roles;
        $usertypes[1]['role'][] = $roles[1];

        $request = $this->createRequest('GET', '/public/api/usertypes');
        $this->withSession(1);
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonData($usertypes, $response);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testListUsertypesWithoutLogin(): void
    {
        $request = $this->createRequest('GET', '/public/api/usertypes');
        $this->withoutSession();
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_UNAUTHORIZED, $response->getStatusCode());
    }
}
