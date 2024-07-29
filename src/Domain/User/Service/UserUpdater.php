<?php

declare(strict_types = 1);

namespace App\Domain\User\Service;

use App\Domain\User\Data\UserData;
use App\Domain\User\Data\UserprefData;
use App\Domain\User\Repository\UserprefRepository;
use App\Domain\User\Repository\UserRepository;
use App\Support\Validation\ValidationException;
use Psr\Log\LoggerInterface;

/**
 * Service.
 */
final class UserUpdater
{
    private UserFinder $userfinder;

    private UserRepository $repository;

    private UserValidator $userValidator;

    private UserprefRepository $userprefrepository;

    private UserprefValidator $userprefValidator;

    private LoggerInterface $logger;

    /**
     * The constructor.
     *
     * @param UserFinder $userfinder The finder repository
     * @param UserRepository $repository The repository
     * @param UserValidator $userValidator The validator
     * @param UserprefRepository $userprefrepository The repository
     * @param UserprefValidator $userprefValidator The validator
     * @param LoggerInterface $loggerinterface The logger
     */
    public function __construct(
        UserFinder $userfinder,
        UserRepository $repository,
        UserValidator $userValidator,
        UserprefRepository $userprefrepository,
        UserprefValidator $userprefValidator,
        LoggerInterface $loggerinterface
    ) {
        $this->userfinder = $userfinder;
        $this->repository = $repository;
        $this->userValidator = $userValidator;
        $this->userprefrepository = $userprefrepository;
        $this->userprefValidator = $userprefValidator;
        $this->logger = $loggerinterface;
    }

    /**
     * Update user.
     *
     * @param int $userId The user id
     * @param array $data The request data
     *
     * @throws ValidationException
     *
     * @return void
     */
    public function updateUser(int $userId, array $data): void
    {
        // Input validation
        if (!$this->repository->existsUserId($userId)) {
            throw new ValidationException(sprintf('User not found: %s', $userId));
        }
        if (!$this->userprefrepository->existsUserprefId($userId)) {
            throw new ValidationException(sprintf('Userpref not found: %s', $userId));
        }
        $this->userValidator->validateUser($data, $this->userprefValidator->getValidateError($data));

        // Validation was successfully
        $user = new UserData($data);
        $user->userid = $userId;
        $userpref = new UserprefData($data);
        $userpref->upref_id = $userId;

        // check email duplication
        $users = $this->userfinder->findUsersByEmail($data['email'] ?? null, $userId);
        if (count($users) > 0) {
            $errors['email'] = ['email' => 'Duplicated email'];
            throw new ValidationException('Please check your input', $errors);
        }

        // Update the user
        $this->repository->updateUser($user);
        $this->userprefrepository->updateUserpref($userpref);

        // Logging
        $this->logger->info(sprintf('User updated successfully: %s', $userId));
    }
}
