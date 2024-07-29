<?php

namespace App\Test\TestCase\Action\User;

use App\Test\Fixture\UsertypeFixture;
use App\Test\Traits\AppTestTrait;
use App\Test\Traits\DBTestTrait;
use App\Test\Traits\SessionTestTrait;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\DatabaseTestTrait;

/**
 * Test.
 *
 * @coversDefaultClass \App\Action\User\UsertypeUpdateAction
 */
class UsertypeUpdateActionTest extends TestCase
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
    public function testUpdateUsertype(): void
    {
        $fixture = (new UsertypeFixture())->records[0];

        $request = $this->createJsonRequest(
            'PUT',
            '/public/api/usertype/1',
            $fixture
        );
        $this->withSession(1);
        $response = $this->app->handle($request);

        // Check response
        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $data = $this->getJsonData($response);
        $this->assertArrayHasKey('error', $data);
        $this->assertFalse($data['error']);

        // Check logger
        $this->assertTrue($this->getLogger()->hasInfoThatContains('Usertype updated successfully'));

        // Check database
        $expected = $fixture;
        $expected['utypeid'] = strval($fixture['utypeid']);
        $expected['roles'] = strval($fixture['roles']);
        $this->assertTableRowByKey($expected, 'usertype', 'utypeid', 1);
        $this->assertTableRowValueByKey('1', 'usertype', 'utypeid', 1, 'utypeid');
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testCreateUsertypeValidation(): void
    {
        $request = $this->createJsonRequest(
            'PUT',
            '/public/api/usertype/1',
            [
                'utypename' => '',
                'roles' => 0,
            ]
        );
        $this->withSession(1);
        $response = $this->app->handle($request);

        // Check response
        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $data = $this->getJsonData($response);
        $this->assertArrayHasKey('error', $data);
        $this->assertTrue($data['error']);
        $this->assertJsonData(
            [
                'error' => true,
                'message' => 'Please check your input',
                'details' => [
                    0 => [
                        'message' => 'Input required',
                        'field' => 'utypename',
                    ],
                    1 => [
                        'message' => 'Invalid roles',
                        'field' => 'roles',
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
    public function testUpdateUsertypeWithoutLogin(): void
    {
        $fixture = (new UsertypeFixture())->records[0];

        $request = $this->createJsonRequest(
            'PUT',
            '/public/api/usertype/1',
            $fixture
        );
        $this->withoutSession();
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_UNAUTHORIZED, $response->getStatusCode());
    }
}
