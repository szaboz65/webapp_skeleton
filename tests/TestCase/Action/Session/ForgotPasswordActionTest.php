<?php

namespace App\Test\TestCase\Action\Session;

use App\Domain\User\Repository\UserpassresetRepository;
use App\Support\W2\RepositoryBase;
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
class ForgotPasswordActionTest extends TestCase
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
    public function testMissingEmail(): void
    {
        $request = $this->createJsonRequest('POST', '/public/api/forgot', [
            'foo' => 'bar',
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
                    'field' => 'email',
                ],
            ],
        ], $response);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testForgotEmptyEmail(): void
    {
        $request = $this->createJsonRequest('POST', '/public/api/forgot', [
            'email' => ' ',
        ]);
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $this->assertJsonData([
            'error' => true,
            'message' => 'Please check your input',
            'details' => [
                ['message' => 'Missing email.', 'field' => 'email'],
            ],
        ], $response);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testForgotUnknownEmail(): void
    {
        $request = $this->createJsonRequest('POST', '/public/api/forgot', [
            'email' => ' bar  ',
        ]);
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $this->assertJsonData([
            'error' => true,
            'message' => 'Please check your input',
            'details' => [
                ['message' => 'Unknown email.', 'field' => 'email'],
            ],
        ], $response);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testGenerateCodeSequence(): void
    {
        $repo = new UserpassresetRepository(
            new RepositoryBase($this->container->get(\App\Factory\QueryFactory::class))
        );
        $repo->deleteUserpassresetById(1);
        $this->__testGenerateCode();
        $this->__testLoadCode();
        $this->__testRegenerateCode();
        $repo->deleteUserpassresetById(1);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function __testGenerateCode(): void
    {
        // generate code
        Chronos::setTestNow('2024-04-01 10:00:00');
        $request = $this->createJsonRequest('POST', '/public/api/forgot', [
            'email' => 'zoltan.szabo65@gmail.com',
        ]);
        $cntBefore = $this->getTableRowCount('pass_reset');
        $response = $this->app->handle($request);
        $cntAfter = $this->getTableRowCount('pass_reset');

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $this->assertJsonData(['error' => false], $response);

        $this->assertEquals(1, $cntAfter - $cntBefore);
        $this->assertTableRowValueByKey('2024-04-03 10:00:00', 'pass_reset', 'userid', 1, 'expire');
    }

    /**
     * Test.
     *
     * @return void
     */
    public function __testLoadCode(): void
    {
        Chronos::setTestNow('2024-04-02 10:00:00');
        $request = $this->createJsonRequest('POST', '/public/api/forgot', [
            'email' => 'zoltan.szabo65@gmail.com',
        ]);
        $cntBefore = $this->getTableRowCount('pass_reset');
        $response = $this->app->handle($request);
        $cntAfter = $this->getTableRowCount('pass_reset');

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $this->assertJsonData(['error' => false], $response);

        $this->assertEquals($cntAfter, $cntBefore);
        $this->assertTableRowValueByKey('2024-04-03 10:00:00', 'pass_reset', 'userid', 1, 'expire');
    }

    /**
     * Test.
     *
     * @return void
     */
    public function __testRegenerateCode(): void
    {
        // delete code and generate new
        Chronos::setTestNow('2024-04-03 12:00:00');
        $request = $this->createJsonRequest('POST', '/public/api/forgot', [
            'email' => 'zoltan.szabo65@gmail.com',
        ]);
        $cntBefore = $this->getTableRowCount('pass_reset');
        $response = $this->app->handle($request);
        Chronos::setTestNow();
        $cntAfter = $this->getTableRowCount('pass_reset');

        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        $this->assertJsonContentType($response);
        $this->assertJsonData(['error' => false], $response);

        $this->assertEquals($cntBefore, $cntAfter);
        $this->assertTableRowValueByKey('2024-04-05 12:00:00', 'pass_reset', 'userid', 1, 'expire');
    }
}
