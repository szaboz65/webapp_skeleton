<?php

declare(strict_types = 1);

namespace App\Domain\User\Service;

use App\Domain\User\Data\UserphotoData;
use App\Domain\User\Repository\UserphotoRepository;
use App\Support\Validation\ValidationException;
use Psr\Log\LoggerInterface;

/**
 * Service.
 */
final class PhotoUpdater
{
    private UserphotoRepository $repository;

    private LoggerInterface $logger;

    /**
     * The constructor.
     *
     * @param UserphotoRepository $repository The repository
     * @param LoggerInterface $loggerInterface The logger
     */
    public function __construct(
        UserphotoRepository $repository,
        LoggerInterface $loggerInterface
    ) {
        $this->repository = $repository;
        $this->logger = $loggerInterface;
    }

    /**
     * Update userphoto.
     *
     * @param int $userId The user id
     * @param array $data The request data
     *
     * @return void
     */
    public function updatePhoto(int $userId, array $data): void
    {
        // file transform to photodata
        if (isset($data['record']) && isset($data['record']['filename'])) {
            $file = &$data['record']['filename'][0];
            $data['photo'] = 'data:' . $file['type'] . ';base64,' . $file['content'];
        }
        // Input validation
        if (!isset($data['photo'])) {
            $error['photo'] = ['_empty' => 'Missing field'];
            throw new ValidationException('Photo not found', $error);
        }

        // Validation was successfully
        $userphoto = new UserphotoData($data);
        $userphoto->userid = $userId;
        if ($this->repository->existsUserphotoId($userId)) {
            // Update the userphoto
            $this->repository->updateUserphoto($userphoto);
            // Logging
            $this->logger->info(sprintf('Userphoto updated successfully: %s', $userId));
        } else {
            // Insert userphoto and get new userphoto ID
            $userphotoId = $this->repository->insertUserphoto($userphoto);
            // Logging
            $this->logger->info(sprintf('Userphoto created successfully: %s', $userphotoId));
        }
    }
}
