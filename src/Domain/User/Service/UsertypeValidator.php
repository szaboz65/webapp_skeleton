<?php

declare(strict_types = 1);

namespace App\Domain\User\Service;

use App\Domain\User\Repository\UsertypeRepository;
use App\Support\Validation\ValidationException;
use Cake\Validation\Validator;

/**
 * Service.
 */
final class UsertypeValidator
{
    private UsertypeRepository $repository;

    /**
     * The constructor.
     *
     * @param UsertypeRepository $repository The repository
     */
    public function __construct(UsertypeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Validate update.
     *
     * @param int $usertypeId The usertype id
     * @param array $data The data
     *
     * @return void
     */
    public function validateUsertypeUpdate(int $usertypeId, array $data): void
    {
        if (!$this->repository->existsUsertypeId($usertypeId)) {
            throw new ValidationException(sprintf('Usertype not found: %s', $usertypeId));
        }

        $this->validateUsertype($data);
    }

    /**
     * Validate new usertype.
     *
     * @param array $data The data
     *
     * @throws ValidationException
     *
     * @return void
     */
    public function validateUsertype(array $data): void
    {
        $errors = $this->createValidator()->validate($data);

        if ($errors) {
            throw new ValidationException('Please check your input', $errors);
        }
    }

    /**
     * Create validator.
     *
     * @return Validator The validator
     */
    private function createValidator(): Validator
    {
        return (new Validator())
            ->notEmptyString('utypename', 'Input required')
            ->inList('roles', [1, 2, 3], 'Invalid roles');
    }
}
