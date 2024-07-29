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
final class UserCreator
{
    private UserFinder $userfinder;

    private UserRepository $userrepository;

    private UserValidator $userValidator;

    private UserprefRepository $userprefrepository;

    private UserprefValidator $userprefValidator;

    private LoggerInterface $logger;

    /**
     * The constructor.
     *
     * @param UserFinder $userfinder The finder repository
     * @param UserRepository $userrepository The repository
     * @param UserValidator $userValidator The validator
     * @param UserprefRepository $userprefrepository The repository
     * @param UserprefValidator $userprefValidator The validator
     * @param LoggerInterface $loggerInterface The logger
     */
    public function __construct(
        UserFinder $userfinder,
        UserRepository $userrepository,
        UserValidator $userValidator,
        UserprefRepository $userprefrepository,
        UserprefValidator $userprefValidator,
        LoggerInterface $loggerInterface
    ) {
        $this->userfinder = $userfinder;
        $this->userrepository = $userrepository;
        $this->userValidator = $userValidator;
        $this->userprefrepository = $userprefrepository;
        $this->userprefValidator = $userprefValidator;
        $this->logger = $loggerInterface;
    }

    /**
     * Create a new user.
     *
     * @param array $data The form data
     *
     * @throws ValidationException
     *
     * @return int The new user ID
     */
    public function createUser(array $data): int
    {
        // Input validation
        $this->userValidator->validateUser($data, $this->userprefValidator->getValidateError($data));

        // Map form data to user DTO (model)
        $user = new UserData($data);
        $userpref = new UserprefData($data);

        // check email duplication
        $users = $this->userfinder->findUsersByEmail($data['email'] ?? null);
        if (count($users) > 0) {
            $errors['email'] = ['email' => 'Duplicated email'];
            throw new ValidationException('Please check your input', $errors);
        }

        // Insert user and get new user ID
        $userId = $this->userrepository->insertUser($user);
        $userpref->upref_id = $userId;
        $this->userprefrepository->insertUserpref($userpref);

        // Logging
        $this->logger->info(sprintf('User created successfully: %s', $userId));

        return $userId;
    }
}
