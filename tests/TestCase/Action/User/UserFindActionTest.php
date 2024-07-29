<?php

namespace App\Test\TestCase\Action\User;

use App\Test\Fixture\UserFixture;
use App\Test\Fixture\UserprefFixture;
use App\Test\Fixture\UsertypeFixture;
use App\Test\Traits\AppTestTrait;
use App\Test\Traits\SessionTestTrait;
use Fig\Http\Message\StatusCodeInterface;
use PHPUnit\Framework\TestCase;
use Selective\TestTrait\Traits\DatabaseTestTrait;

/**
 * Test.
 *
 * @coversDefaultClass \App\Action\User\UserFindAction
 */
class UserFindActionTest extends TestCase
{
    use AppTestTrait;
    use DatabaseTestTrait;
    use SessionTestTrait;

    /**
     * Test.
     *
     * @return void
     */
    public function testListUsersEmptyRequest(): void
    {
        $request = $this->createRequest('GET', '/public/api/users');
        $this->withSession(1);
        $response = $this->app->handle($request);

        $expected = [];
        $records = (new UserFixture())->records;
        $usertypes = (new UsertypeFixture())->records;
        $userprefs = (new UserprefFixture())->records;
        $i = 0;
        foreach ($records as &$record) {
            foreach ($usertypes as &$usertype) {
                if ($usertype['utypeid'] == $record['fk_utypeid']) {
                    $expected[] = array_merge($record, $usertype, $userprefs[$i]);
                    break;
                }
            }
            $i++;
        }
        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        // $this->assertJsonData($expected, $response);
        // $data = $this->getJsonData($response);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testListUsers(): void
    {
        // phpcs:ignore Generic.Files.LineLength
        $request = $this->createRequest('GET', '/public/api/users?request=%7B%22limit%22%3A100%2C%22offset%22%3A0%2C%22sort%22%3A%5B%7B%22field%22%3A%22name%22%2C%22direction%22%3A%22asc%22%7D%5D%7D');
        $this->withSession(1);
        $response = $this->app->handle($request);

        $expected = [];
        $records = (new UserFixture())->records;
        $usertypes = (new UsertypeFixture())->records;
        $userprefs = (new UserprefFixture())->records;
        $i = 0;
        foreach ($records as &$record) {
            foreach ($usertypes as &$usertype) {
                if ($usertype['utypeid'] == $record['fk_utypeid']) {
                    $expected[] = array_merge($record, $usertype, $userprefs[$i]);
                    break;
                }
            }
            $i++;
        }
        $this->assertSame(StatusCodeInterface::STATUS_OK, $response->getStatusCode());
        // $this->assertJsonData($expected, $response);
        // $data = $this->getJsonData($response);
    }

    /**
     * Test.
     *
     * @return void
     */
    public function testListUsersWithoutLogin(): void
    {
        $request = $this->createRequest('GET', '/public/api/users');
        $this->withoutSession();
        $response = $this->app->handle($request);

        $this->assertSame(StatusCodeInterface::STATUS_UNAUTHORIZED, $response->getStatusCode());
    }
}
