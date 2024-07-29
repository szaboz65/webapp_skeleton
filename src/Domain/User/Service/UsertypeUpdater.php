<?php

declare(strict_types = 1);

namespace App\Domain\User\Service;

use App\Domain\User\Data\UsertypeData;
use App\Domain\User\Repository\UsertypeRepository;
use Psr\Log\LoggerInterface;

/**
 * Service.
 */
final class UsertypeUpdater
{
    private UsertypeRepository $repository;

    private UsertypeValidator $usertypeValidator;

    private LoggerInterface $logger;

    /**
     * The constructor.
     *
     * @param UsertypeRepository $repository The repository
     * @param UsertypeValidator $usertypeValidator The validator
     * @param LoggerInterface $loggerinterface The logger
     */
    public function __construct(
        UsertypeRepository $repository,
        UsertypeValidator $usertypeValidator,
        LoggerInterface $loggerinterface
    ) {
        $this->repository = $repository;
        $this->usertypeValidator = $usertypeValidator;
        $this->logger = $loggerinterface;
    }

    /**
     * Update usertype.
     *
     * @param int $usertypeId The usertype id
     * @param array $data The request data
     *
     * @return void
     */
    public function updateUsertype(int $usertypeId, array $data): void
    {
        // Input validation
        $this->usertypeValidator->validateUsertypeUpdate($usertypeId, $data);

        // Validation was successfully
        $usertype = new UsertypeData($data);
        $usertype->utypeid = $usertypeId;

        // Update the user
        $this->repository->updateUsertype($usertype);

        // Logging
        $this->logger->info(sprintf('Usertype updated successfully: %s', $usertypeId));
    }
}
