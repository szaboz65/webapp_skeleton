<?php

namespace App\Test\TestCase\Action\User;

use App\Domain\User\Type\UserType;
use App\Test\Traits\AppTestTrait;
use App\Test\Traits\DBTestTrait;
use App\Test\Traits\SessionTestTrait;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\DatabaseTestTrait;

/**
 * Test.
 *
 * @coversDefaultClass \App\Action\User\UserUpdateAction
 */
class UserUpdateActionTest extends TestCase
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
    public function testUpdateUser(): void
    {
        $request = $this->createJsonRequest(
            'PUT',
            '/public/api/user/1',
            [
                'userid' => 1,
                'fk_utypeid' => UserType::USERTYPE_OTHER,
                'name' => 'admin',
                'phone' => '87654321',
                'title' => 'title',
                'email' => 'mail@example.com',
                'inactive' => 1,
                'super' => 0,
            ]
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
        $this->assertTrue($this->getLogger()->hasInfoThatContains('User updated successfully'));

        // Check database
        $userid = 1;
        $expected = [
            'userid' => strval($userid),
            'fk_utypeid' => '2',
            'name' => 'admin',
            'phone' => '87654321',
            'title' => 'title',
            'email' => 'mail@example.com',
            'inactive' => '1',
            'super' => '0',
        ];

        $this->assertTableRowByKey($expected, 'user', 'userid', $userid);
        $this->assertTableRowValueByKey('1', 'user', 'userid', $userid, 'userid');

        $expectedpref = [
            'upref_id' => strval($userid),
            'locale' => 'en-US',
            'schema' => 'normal',
        ];
        $this->assertTableRowByKey($expectedpref, 'userpref', 'upref_id', $userid);
        $this->assertTableRowValueByKey(strval($userid), 'userpref', 'upref_id', $userid, 'upref_id');
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testUpdateUserValidation(): void
    {
        // $this->insertFixtures([UserFixture::class]);

        $request = $this->createJsonRequest(
            'PUT',
            '/public/api/user/1',
            [
                'fk_utypeid' => 99,
                'name' => '',
                'phone' => '',
                'email' => 'mail',
                'title' => '',
                'inactive' => 'a',
                'super' => 'b',
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
                        'field' => 'fk_utypeid',
                    ],
                    1 => [
                        'message' => 'Input required',
                        'field' => 'name',
                    ],
                    2 => [
                        'message' => 'Input required',
                        'field' => 'phone',
                    ],
                    3 => [
                        'message' => 'Input required',
                        'field' => 'title',
                    ],
                    4 => [
                        'message' => 'Email required',
                        'field' => 'email',
                    ],
                    5 => [
                        'message' => 'Invalid',
                        'field' => 'inactive',
                    ],
                    6 => [
                        'message' => 'Invalid',
                        'field' => 'super',
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
    public function testUpdateUserDuplicatedEmail(): void
    {
        $request = $this->createJsonRequest(
            'PUT',
            '/public/api/user/1',
            [
                'fk_utypeid' => 1,
                'name' => 'Name',
                'phone' => '123',
                'email' => 'user.other@mail.com',
                'title' => 'title',
                'inactive' => '0',
                'super' => '0',
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
                        'message' => 'Duplicated email',
                        'field' => 'email',
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
    public function testUpdateUserWithoutLogin(): void
    {
        $request = $this->createJsonRequest(
            'PUT',
            '/public/api/user/1',
            [
                'fk_utypeid' => UserType::USERTYPE_OTHER,
                'name' => 'admin',
                'phone' => '87654321',
                'title' => 'title',
                'email' => 'mail@example.com',
                'inactive' => 1,
                'super' => 0,
            ]
        );
        $this->withoutSession();
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_UNAUTHORIZED, $response->getStatusCode());
    }
}
