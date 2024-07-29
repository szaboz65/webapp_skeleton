<?php

namespace App\Test\TestCase\Action\Session;

use App\Test\Fixture\UserfailFixture;
use App\Test\Traits\AppTestTrait;
use App\Test\Traits\DBTestTrait;
use Cake\Chronos\Chronos;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\DatabaseTestTrait;

/**
 * Test.
 */
class LoginActionTest extends TestCase
{
    use AppTestTrait;
    use DatabaseTestTrait;
    use DBTestTrait;

    /**
     * Test.
     *
     * @return void
     */
    public function testMissingLoginAndPass(): void
    {
        $request = $this->createJsonRequest('POST', '/public/api/login', [
            'foo' => 'foo',
            'bar' => 'bar',
        ]);
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $this->assertJsonData([
            'error' => true,
            'message' => 'Please check your input',
            'details' => [
                0 => [
                    'message' => 'This field is required',
                    'field' => 'login',
                ],
                1 => [
                    'message' => 'This field is required',
                    'field' => 'pass',
                ],
            ],
        ], $response);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testNotEmailAndSortPass(): void
    {
        $request = $this->createJsonRequest('POST', '/public/api/login', [
            'login' => 'foo',
            'pass' => 'bar',
        ]);
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $this->assertJsonData([
            'error' => true,
            'message' => 'Please check your input',
            'details' => [
                0 => [
                    'message' => 'Email required.',
                    'field' => 'login',
                ],
                1 => [
                    'message' => 'Password is too sort.',
                    'field' => 'pass',
                ],
            ],
        ], $response);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testUserNotFound(): void
    {
        $request = $this->createJsonRequest('POST', '/public/api/login', [
            'login' => 'foo@bar.hu',
            'pass' => 'bar_baz2',
        ]);
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $this->assertJsonData([
            'error' => true,
            'message' => 'User not found: foo@bar.hu',
        ], $response);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testTooManyAttempt(): void
    {
        $cnt = $this->getTableRowCount('userfail');
        if ($cnt == 0) {
            $this->insertFixtures([UserfailFixture::class]);
        }
        Chronos::setTestNow('2024-04-01 10:03:00');

        $request = $this->createJsonRequest('POST', '/public/api/login', [
            'login' => 'zoltan.szabo65@gmail.com',
            'pass' => 'bar_baz2',
        ]);
        $response = $this->app->handle($request);
        Chronos::setTestNow();

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $this->assertJsonData([
            'error' => true,
            'message' => 'Too many attempt.',
        ], $response);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testPassExpired(): void
    {
        Chronos::setTestNow('2025-04-01 10:00:00');
        $cntBefore = $this->getTableRowCount('userfail');
        $request = $this->createJsonRequest('POST', '/public/api/login', [
            'login' => 'zoltan.szabo65@gmail.com',
            'pass' => 'bar_bazz2',
        ]);
        $response = $this->app->handle($request);
        Chronos::setTestNow();

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $this->assertJsonData([
            'error' => true,
            'message' => 'Password is expired.',
        ], $response);

        $cntAfter = $this->getTableRowCount('userfail');
        $this->assertEquals($cntBefore + 1, $cntAfter);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testPassMismatch(): void
    {
        $cnt = $this->getTableRowCount('usersecret');
        $this->assertEquals(2, $cnt);
        $cntBefore = $this->getTableRowCount('userfail');
        Chronos::setTestNow('2024-04-01 10:00:00');
        $request = $this->createJsonRequest('POST', '/public/api/login', [
            'login' => 'zoltan.szabo65@gmail.com',
            'pass' => 'foo-bar1',
        ]);
        $response = $this->app->handle($request);
        Chronos::setTestNow();

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $this->assertJsonData([
            'error' => true,
            'message' => 'Password is wrong.',
        ], $response);

        $cntAfter = $this->getTableRowCount('userfail');
        $this->assertEquals($cntBefore + 1, $cntAfter);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testLogin(): void
    {
        $cnt = $this->getTableRowCount('usersecret');
        $this->assertEquals(2, $cnt);
        $cntBefore = $this->getTableRowCount('userfail');
        if ($cntBefore == 0) {
            $this->insertFixtures([UserfailFixture::class]);
            $cntBefore = $this->getTableRowCount('userfail');
        }
        $this->assertEquals(5, $cntBefore);
        Chronos::setTestNow('2024-04-01 10:00:00');
        $request = $this->createJsonRequest('POST', '/public/api/login', [
            'login' => 'zoltan.szabo65@gmail.com',
            'pass' => '23456789',
        ]);
        $response = $this->app->handle($request);
        Chronos::setTestNow();

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $data = $this->getJsonData($response);
        $this->assertArrayHasKey('error', $data);
        $this->assertFalse($data['error']);

        $cntAfter = $this->getTableRowCount('userfail');
        $this->assertEquals(0, $cntAfter);

        $expected_session = [
            'ses_userid' => '1',
            'ses_lastlogin' => '2024-04-01 10:00:00',
            'ses_lastactive' => '2024-04-01 10:00:00',
            'ses_expire' => '2024-04-01 11:00:00',
        ];
        $this->assertTableRowExistsByKey('usersession', 'ses_userid', 1);
        $this->assertTableRowByKey($expected_session, 'usersession', 'ses_userid', 1);

        $session = $this->container->get(\App\Domain\Session\Session\SessionInterface::class);
        $this->assertTrue($session->has('userId'));
        $this->assertEquals(1, $session->get('userId'));
    }
}
