<?php

namespace App\Test\TestCase\Action\User;

use App\Test\Fixture\UserprefFixture;
use App\Test\Traits\AppTestTrait;
use App\Test\Traits\DBTestTrait;
use App\Test\Traits\SessionTestTrait;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\DatabaseTestTrait;

/**
 * Test.
 *
 * @coversDefaultClass \App\Action\User\UserprefUpdateAction
 */
class UserprefUpdateActionTest extends TestCase
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
    public function testUpdateUserpref(): void
    {
        $fixture = (new UserprefFixture())->records[0];

        $request = $this->createJsonRequest(
            'PUT',
            '/public/api/user/1/pref',
            $fixture
        );
        $this->withSession(1);
        $response = $this->app->handle($request);

        // Check response
        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);

        // Check logger
        $this->assertTrue($this->getLogger()->hasInfoThatContains('Userpref updated successfully'));

        // Check database
        $expected = $fixture;
        $expected['upref_id'] = strval($fixture['upref_id']);
        $this->assertTableRowByKey($expected, 'userpref', 'upref_id', 1);
        $this->assertTableRowValueByKey('1', 'userpref', 'upref_id', 1, 'upref_id');
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testCreateUserprefValidation(): void
    {
        $request = $this->createJsonRequest(
            'PUT',
            '/public/api/user/1/pref',
            [
                'locale' => 'de-DE',
                'schema' => 'pink',
            ]
        );
        $this->withSession(1);
        $response = $this->app->handle($request);

        // Check response
        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $this->assertJsonData(
            [
                'error' => true,
                'message' => 'Please check your input',
                'details' => [
                    0 => [
                        'message' => 'Invalid',
                        'field' => 'locale',
                    ],
                    1 => [
                        'message' => 'Invalid',
                        'field' => 'schema',
                    ],
                ],
            ],
            $response
        );
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testListRolesWithoutLogin(): void
    {
        $fixture = (new UserprefFixture())->records[0];

        $request = $this->createJsonRequest(
            'PUT',
            '/public/api/user/1/pref',
            $fixture
        );
        $this->withoutSession();
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_UNAUTHORIZED, $response->getStatusCode());
    }
}
