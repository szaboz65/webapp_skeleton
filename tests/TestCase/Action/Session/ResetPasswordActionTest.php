<?php

namespace App\Test\TestCase\Action\Session;

use App\Test\Traits\AppTestTrait;
use App\Test\Traits\DBTestTrait;
use App\Test\Traits\SessionTestTrait;
use Cake\Chronos\Chronos;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\DatabaseTestTrait;

/**
 * Test.
 */
class ResetPasswordActionTest extends TestCase
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
    public function testMissingFields(): void
    {
        $request = $this->createJsonRequest('POST', '/public/api/pwreset');
        $this->withSession(1);
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $this->assertJsonData([
            'error' => true,
            'message' => 'Please check your input',
            'details' => [
                0 => [
                    'message' => 'This field is required',
                    'field' => 'pwemail',
                ],
                1 => [
                    'message' => 'This field is required',
                    'field' => 'reset_code',
                ],
                2 => [
                    'message' => 'This field is required',
                    'field' => 'pw',
                ],
            ],
        ], $response);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function ____testEmptyFields(): void
    {
        $request = $this->createJsonRequest('POST', '/public/api/pwreset', [
            'pwemail' => '  ',
            'reset_code' => '  ',
            'pw' => ' ',
        ]);
        $this->withSession(1);
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $this->assertJsonData([
            'error' => true,
            'message' => 'Please check your input',
            'details' => [
                0 => [
                    'message' => 'Input required.',
                    'field' => 'pwemail',
                ],
                1 => [
                    'message' => 'Input required.',
                    'field' => 'reset_code',
                ],
                2 => [
                    'message' => 'Input required.',
                    'field' => 'pw',
                ],
            ],
        ], $response);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testBadFields(): void
    {
        $request = $this->createJsonRequest('POST', '/public/api/pwreset', [
            'pwemail' => 'a',
            'reset_code' => '2',
            'pw' => '2',
        ]);
        $this->withSession(1);
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $this->assertJsonData([
            'error' => true,
            'message' => 'Please check your input',
            'details' => [
                0 => [
                    'message' => 'Email required.',
                    'field' => 'pwemail',
                ],
                1 => [
                    'message' => 'Code required.',
                    'field' => 'reset_code',
                ],
                2 => [
                    'message' => 'Password need in range [8, 40] character',
                    'field' => 'pw',
                ],
            ],
        ], $response);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testUnknownEmail(): void
    {
        $request = $this->createJsonRequest('POST', '/public/api/pwreset', [
            'pwemail' => 'a12@gmail.com',
            'reset_code' => 'f47ac10b-58cc-4372-a567-0e02b2c3d479',
            'pw' => 'azAZ09!?new',
        ]);
        $this->withSession(1);
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $this->assertJsonData([
            'error' => true,
            'message' => 'Unknown email.',
        ], $response);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testNotsent(): void
    {
        $request = $this->createJsonRequest('POST', '/public/api/pwreset', [
            'pwemail' => 'zoltan.szabo65@gmail.com',
            'reset_code' => 'f47ac10b-58cc-4372-a567-0e02b2c3d479',
            'pw' => 'azAZ09!?new',
        ]);
        $this->withSession(1);
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $this->assertJsonData([
            'error' => true,
            'message' => 'Reset code is not sent.',
        ], $response);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testExpired(): void
    {
        $this->assertEquals(0, $this->getTableRowCount('pass_reset'));
        $this->insertFixture('pass_reset', [
            'userid' => 1,
            'expire' => '2024-01-01 00:00:00',
            'reset_code' => 'f47ac10b-58cc-4372-a567-0e02b2c3d479',
        ]);
        $request = $this->createJsonRequest('POST', '/public/api/pwreset', [
            'pwemail' => 'zoltan.szabo65@gmail.com',
            'reset_code' => 'f47ac10b-58cc-4372-a567-0e02b2c3d479',
            'pw' => 'azAZ09!?new',
        ]);
        $this->withSession(1);
        $response = $this->app->handle($request);
        $this->deleteTableRowByKeyAndId('pass_reset', 'userid', 1);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $this->assertJsonData([
            'error' => true,
            'message' => 'The reset code is expired.',
        ], $response);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testDifferentResetCode(): void
    {
        $this->assertEquals(0, $this->getTableRowCount('pass_reset'));
        $this->insertFixture('pass_reset', [
            'userid' => 1,
            'expire' => '2025-01-01 00:00:00',
            'reset_code' => 'f47ac10b-58cc-4372-a567-0e02b2c3d47+',
        ]);
        $request = $this->createJsonRequest('POST', '/public/api/pwreset', [
            'pwemail' => 'zoltan.szabo65@gmail.com',
            'reset_code' => 'f47ac10b-58cc-4372-a567-0e02b2c3d479',
            'pw' => 'azAZ09!?new',
        ]);
        $this->withSession(1);
        $response = $this->app->handle($request);
        $this->deleteTableRowByKeyAndId('pass_reset', 'userid', 1);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $this->assertJsonData([
            'error' => true,
            'message' => 'The reset code is mismatched.',
        ], $response);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testPasswordRule(): void
    {
        $this->assertEquals(0, $this->getTableRowCount('pass_reset'));
        $this->insertFixture('pass_reset', [
            'userid' => 1,
            'expire' => '2025-01-01 00:00:00',
            'reset_code' => 'f47ac10b-58cc-4372-a567-0e02b2c3d479',
        ]);
        $request = $this->createJsonRequest('POST', '/public/api/pwreset', [
            'pwemail' => 'zoltan.szabo65@gmail.com',
            'reset_code' => 'f47ac10b-58cc-4372-a567-0e02b2c3d479',
            'pw' => 'Qwert12346',
        ]);
        $this->withSession(1);
        $response = $this->app->handle($request);
        $this->deleteTableRowByKeyAndId('pass_reset', 'userid', 1);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $this->assertJsonData([
            'error' => true,
            'message' => 'The password does not fit the rules.',
        ], $response);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testEarlierPassword(): void
    {
        $this->assertEquals(0, $this->getTableRowCount('pass_reset'));
        $this->insertFixture('pass_reset', [
            'userid' => 1,
            'expire' => '2025-01-01 00:00:00',
            'reset_code' => 'f47ac10b-58cc-4372-a567-0e02b2c3d479',
        ]);
        $request = $this->createJsonRequest('POST', '/public/api/pwreset', [
            'pwemail' => 'zoltan.szabo65@gmail.com',
            'reset_code' => 'f47ac10b-58cc-4372-a567-0e02b2c3d479',
            'pw' => 'azAZ09!?',
        ]);
        $this->withSession(1);
        Chronos::setTestNow('2024-04-01 10:00:00');
        $response = $this->app->handle($request);
        $this->deleteTableRowByKeyAndId('pass_reset', 'userid', 1);
        Chronos::setTestNow();

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $this->assertJsonData([
            'error' => true,
            'message' => 'The password has been used earlier.',
        ], $response);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testInvalidateEarlierAndStorePassword(): void
    {
        $this->assertEquals(0, $this->getTableRowCount('pass_reset'));
        $this->assertEquals(2, $this->getTableRowCount('usersecret'));
        $this->insertFixture('pass_reset', [
            'userid' => 1,
            'expire' => '2025-01-01 00:00:00',
            'reset_code' => 'f47ac10b-58cc-4372-a567-0e02b2c3d479',
        ]);
        $request = $this->createJsonRequest('POST', '/public/api/pwreset', [
            'pwemail' => 'zoltan.szabo65@gmail.com',
            'reset_code' => 'f47ac10b-58cc-4372-a567-0e02b2c3d479',
            'pw' => 'azAZ09!?new',
        ]);
        $this->withSession(1);
        Chronos::setTestNow('2024-04-01 10:00:00');
        $response = $this->app->handle($request);
        Chronos::setTestNow();

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $this->assertJsonData([
            'error' => false,
        ], $response);

        $this->assertEquals(0, $this->getTableRowCount('pass_reset'));
        $this->assertEquals(3, $this->getTableRowCount('usersecret'));
    }
}
