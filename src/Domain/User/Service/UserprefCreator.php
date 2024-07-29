<?php

declare(strict_types = 1);

namespace App\Domain\User\Service;

use App\Domain\User\Data\UserprefData;
use App\Domain\User\Repository\UserprefRepository;
use Psr\Log\LoggerInterface;

/**
 * Service.
 */
final class UserprefCreator
{
    private UserprefRepository $repository;

    private UserprefValidator $validator;

    private LoggerInterface $logger;

    /**
     * The constructor.
     *
     * @param UserprefRepository $repository The repository
     * @param UserprefValidator $validator The validator
     * @param LoggerInterface $loggerInterface The logger
     */
    public function __construct(
        UserprefRepository $repository,
        UserprefValidator $validator,
        LoggerInterface $loggerInterface
    ) {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->logger = $loggerInterface;
    }

    /**
     * Create a new userpref.
     *
     * @param array $data The form data
     *
     * @return int The new userpref ID
     */
    public function createUser(array $data): int
    {
        // Input validation
        $this->validator->validateUserpref($data);

        // Map form data to userpref DTO (model)
        $userpref = new UserprefData($data);

        // Insert userpref and get new userpref ID
        $userprefId = $this->repository->insertUserpref($userpref);

        // Logging
        $this->logger->info(sprintf('Userpref created successfully: %s', $userprefId));

        return $userprefId;
    }
}
