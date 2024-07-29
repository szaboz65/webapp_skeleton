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
 * @coversDefaultClass \App\Action\User\UserCreateAction
 */
class UserCreateActionTest extends TestCase
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
    public function testCreateUser(): void
    {
        $count = $this->getTableRowCount('user');
        $countpref = $this->getTableRowCount('userpref');
        $user_id = $count + 1;
        $this->assertEquals($count, $countpref);

        $request = $this->createJsonRequest(
            'POST',
            '/public/api/user',
            [
                'fk_utypeid' => UserType::USERTYPE_OTHER,
                'name' => 'username',
                'phone' => '12345678',
                'title' => 'Sally',
                'email' => 'mail@example.com',
                'inactive' => '0',
                'super' => '0',
            ]
        );
        $this->withSession(1);

        $response = $this->app->handle($request);

        // Check response
        // $this->assertSame(StatusCodeInterface::STATUS_CREATED, $response->getStatusCode());
        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $this->assertJsonData(['lastinsertedid' => $user_id], $response);

        // Check logger
        $this->assertTrue($this->getLogger()->hasInfoThatContains('User created successfully'));

        // Check database
        $this->assertTableRowCount($count + 1, 'user');
        $this->assertTableRowCount($count + 1, 'userpref');

        $expected = [
            'userid' => strval($user_id),
            'fk_utypeid' => strval(UserType::USERTYPE_OTHER),
            'name' => 'username',
            'phone' => '12345678',
            'title' => 'Sally',
            'email' => 'mail@example.com',
            'inactive' => '0',
            'super' => '0',
        ];
        $this->assertTableRowByKey($expected, 'user', 'userid', $user_id);
        $this->assertTableRowValueByKey(strval($user_id), 'user', 'userid', $user_id, 'userid');

        $expectedpref = [
            'upref_id' => $expected['userid'],
            'locale' => 'en-US',
            'schema' => 'normal',
        ];
        $this->assertTableRowByKey($expectedpref, 'userpref', 'upref_id', $user_id);
        $this->assertTableRowValueByKey(strval($user_id), 'userpref', 'upref_id', $user_id, 'upref_id');
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testCreateUserValidation(): void
    {
        $request = $this->createJsonRequest(
            'POST',
            '/public/api/user',
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
    public function testCreateUserDuplicatedEmail(): void
    {
        $request = $this->createJsonRequest(
            'POST',
            '/public/api/user',
            [
                'fk_utypeid' => 1,
                'name' => 'Name',
                'phone' => '123',
                'email' => 'zoltan.szabo65@gmail.com',
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
    public function testCreateUserWithoutLogin(): void
    {
        $request = $this->createJsonRequest(
            'POST',
            '/public/api/user',
            [
                'fk_utypeid' => UserType::USERTYPE_OTHER,
                'name' => 'username',
                'phone' => '12345678',
                'title' => 'Sally',
                'email' => 'mail@example.com',
                'inactive' => '0',
                'super' => '0',
            ]
        );
        $this->withoutSession();
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_UNAUTHORIZED, $response->getStatusCode());
    }
}
