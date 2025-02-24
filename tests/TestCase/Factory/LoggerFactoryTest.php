<?php

namespace App\Test\TestCase\Factory;

use App\Factory\LoggerFactory;
use App\Test\Traits\AppTestTrait;
use Monolog\Handler\TestHandler;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 */
class LoggerFactoryTest extends TestCase
{
    use AppTestTrait;

    private string $temp = '';

    /**
     * Set up.
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->temp = vfsStream::setup()->url();
    }

    /**
     * Test.
     *
     * @return void
     */
    public function test(): void
    {
        $this->expectOutputRegex('/INFO: Info message/');
        $this->expectOutputRegex('/ERROR: Error message/');

        $settings = [
            'path' => $this->temp,
            'level' => 0,
        ];

        $factory = new LoggerFactory($settings);

        $testHandler = new TestHandler();
        $factory
            ->addHandler($testHandler)
            ->addFileHandler('test.log')
            ->addConsoleHandler();

        $logger = $factory->createLogger();
        $logger->info('Info message');
        $logger->error('Error message');

        $this->assertTrue($testHandler->hasInfo('Info message'));
        $this->assertTrue($testHandler->hasError('Error message'));

        $now = (new \DateTimeImmutable())->format('Y-m-d');
        $this->assertFileExists(sprintf('%s/test-%s.log', $this->temp, $now));
    }
}
