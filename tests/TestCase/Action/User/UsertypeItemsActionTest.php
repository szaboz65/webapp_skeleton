<?php

declare(strict_types = 1);

namespace App\Test\TestCase\Action\User;

use App\Test\Fixture\UsertypeFixture;
use App\Test\Traits\AppTestTrait;
use App\Test\Traits\SessionTestTrait;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\DatabaseTestTrait;

/**
 * Test.
 *
 * @coversDefaultClass \App\Action\User\UsertypeItemsAction
 */
class UsertypeItemsActionTest extends TestCase
{
    use AppTestTrait;
    use DatabaseTestTrait;
    use SessionTestTrait;

    /**
     * Test.
     *
     * @return void
     */
    public function testUsertypeItems(): void
    {
        $request = $this->createRequest('GET', '/public/api/usertypeitems');
        $this->withSession(1);
        $response = $this->app->handle($request);

        $fixtures = (new UsertypeFixture())->records;
        $expected = [
            [
                'id' => $fixtures[0]['utypeid'],
                'text' => $fixtures[0]['utypename'],
            ],
            [
                'id' => $fixtures[1]['utypeid'],
                'text' => $fixtures[1]['utypename'],
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
    public function testUsertypeItemsWithoutLogin(): void
    {
        $request = $this->createRequest('GET', '/public/api/usertypeitems');
        $this->withoutSession();
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_UNAUTHORIZED, $response->getStatusCode());
    }
}
