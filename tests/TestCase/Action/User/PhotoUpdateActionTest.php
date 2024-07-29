<?php

namespace App\Test\TestCase\Action\User;

use App\Test\Fixture\UserphotoFixture;
use App\Test\Traits\AppTestTrait;
use App\Test\Traits\DBTestTrait;
use App\Test\Traits\SessionTestTrait;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\DatabaseTestTrait;

/**
 * Test.
 *
 * @coversDefaultClass \App\Action\User\UserphotoUpdateAction
 */
class PhotoUpdateActionTest extends TestCase
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
    public function testUpdateUserphoto(): void
    {
        $fixture = (new UserphotoFixture())->records[0];

        $request = $this->createJsonRequest(
            'PUT',
            '/public/api/user/1/photo',
            ['photo' => $fixture['photo']]
        );
        $this->withSession(1);
        $response = $this->app->handle($request);

        // Check response
        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);

        // Check logger
        $this->assertTrue($this->getLogger()->hasInfoThatContains('Userphoto updated successfully'));

        // Check database
        $expected = $fixture;
        $expected['userid'] = strval($fixture['userid']);
        $this->assertTableRowByKey($expected, 'photo', 'userid', 1);
        $this->assertTableRowValueByKey('1', 'photo', 'userid', 1, 'userid');
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testUpdateUserphotoFile(): void
    {
        $fixture = (new UserphotoFixture())->filerecord;

        $request = $this->createJsonRequest(
            'PUT',
            '/public/api/user/2/photo',
            $fixture
        );
        $this->withSession(1);
        $response = $this->app->handle($request);

        // Check response
        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);

        // Check logger
        $this->assertTrue($this->getLogger()->hasInfoThatContains('Userphoto created successfully'));

        // Check database
        $expected = [
            'userid' => '2',
            'photo' => (new UserphotoFixture())->records[0]['photo'],
        ];
        $this->assertTableRowByKey($expected, 'photo', 'userid', 2);
        $this->assertTableRowValueByKey('2', 'photo', 'userid', 2, 'userid');
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testCreateUserphotoValidation(): void
    {
        $request = $this->createJsonRequest(
            'PUT',
            '/public/api/user/1/photo',
            [
                'foo' => 'bar',
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
                'message' => 'Photo not found',
                'details' => [
                    0 => [
                        'message' => 'Missing field',
                        'field' => 'photo',
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
    public function testUpdatePhotoWithoutLogin(): void
    {
        $fixture = (new UserphotoFixture())->filerecord;

        $request = $this->createJsonRequest(
            'PUT',
            '/public/api/user/1/photo',
            $fixture
        );
        $this->withoutSession();
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_UNAUTHORIZED, $response->getStatusCode());
    }
}
