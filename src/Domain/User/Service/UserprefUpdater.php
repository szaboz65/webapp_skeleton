<?php

declare(strict_types = 1);

namespace App\Domain\User\Service;

use App\Domain\User\Data\UserprefData;
use App\Domain\User\Repository\UserprefRepository;
use App\Support\Validation\ValidationException;
use Psr\Log\LoggerInterface;

/**
 * Service.
 */
final class UserprefUpdater
{
    private UserprefRepository $repository;

    private UserprefValidator $validator;

    private LoggerInterface $logger;

    /**
     * The constructor.
     *
     * @param UserprefRepository $repository The repository
     * @param UserprefValidator $validator The validator
     * @param LoggerInterface $loggerinterface The logger
     */
    public function __construct(
        UserprefRepository $repository,
        UserprefValidator $validator,
        LoggerInterface $loggerinterface
    ) {
        $this->repository = $repository;
        $this->validator = $validator;
        $this->logger = $loggerinterface;
    }

    /**
     * Update userpref.
     *
     * @param int $userprefId The userpref id
     * @param array $data The request data
     *
     * @return void
     */
    public function updateUserpref(int $userprefId, array $data): void
    {
        if (!$this->repository->existsUserprefId($userprefId)) {
            throw new ValidationException(sprintf('Userpref not found: %s', $userprefId));
        }
        // Input validation
        $this->validator->validateUserpref($data);

        // Validation was successfully
        $userpref = new UserprefData($data);
        $userpref->upref_id = $userprefId;

        // Update the user
        $this->repository->updateUserpref($userpref);

        // Logging
        $this->logger->info(sprintf('Userpref updated successfully: %s', $userprefId));
    }
}
