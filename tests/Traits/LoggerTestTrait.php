<?php

namespace App\Test\Traits;

use App\Factory\LoggerFactory;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

/**
 * Test trait.
 */
trait LoggerTestTrait
{
    protected TestHandler $testHandler;

    /**
     * Add test logger.
     *
     * @return void
     */
    protected function setUpLogger(): void
    {
        $this->testHandler = new TestHandler();
        $logger = new Logger('', [$this->testHandler]);
        $this->setContainerValue(LoggerInterface::class, $logger);

        $loggerFactory = new LoggerFactory(['test' => $logger]);
        $this->setContainerValue(LoggerFactory::class, $loggerFactory);
    }

    /**
     * Get test logger handler.
     *
     * @return TestHandler The logger
     */
    protected function getLogger(): TestHandler
    {
        return $this->testHandler;
    }
}
