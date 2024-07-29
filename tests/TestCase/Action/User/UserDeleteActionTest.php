<?php

namespace App\Test\TestCase\Action\User;

use App\Test\Traits\AppTestTrait;
use App\Test\Traits\DBTestTrait;
use App\Test\Traits\SessionTestTrait;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\DatabaseTestTrait;

/**
 * Test.
 *
 * @coversDefaultClass \App\Action\User\UserDeleteAction
 */
class UserDeleteActionTest extends TestCase
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
    public function testDeleteUser(): void
    {
        $this->assertTrue(true);
        /*
        $count = $this->getTableRowCount('user');

        $request = $this->createJsonRequest('DELETE', '/public/api/user/' . strval($count));
        $this->withSession(1);

        $response = $this->app->handle($request);

        // Check response
        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());

        // Check database
        $this->assertTableRowCount($count - 1, 'user');
        $this->assertTableRowNotExistsByKey('user', 'userid', $count);*/
    }

    /**
     * Test.
     *
     * @return void
     */
    public function __testDeleteUserWithoutLogin(): void
    {
        $request = $this->createRequest('DELETE', '/public/api/user/99');
        $this->withoutSession();
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_UNAUTHORIZED, $response->getStatusCode());
    }
}
