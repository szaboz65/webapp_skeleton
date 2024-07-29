<?php

declare(strict_types = 1);

namespace App\Domain\User\Service;

use App\Domain\User\Type\UserType;
use App\Support\Validation\ValidationException;
use Cake\Validation\Validator;

/**
 * Service.
 */
final class UserValidator
{
    /**
     * Validate new user.
     *
     * @param array $data The data
     * @param array $errors The previous errors
     *
     * @throws ValidationException
     *
     * @return void
     */
    public function validateUser(array $data, array $errors = []): void
    {
        $errors = array_merge($this->createValidator()->validate($data), $errors);

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
            ->inList('fk_utypeid', [UserType::USERTYPE_OTHER, UserType::USERTYPE_ADMIN], 'Invalid')
            ->notEmptyString('name', 'Input required')
            ->notEmptyString('phone', 'Input required')
            ->notEmptyString('title', 'Input required')
            ->email('email', false, 'Email required')
            ->inList('inactive', [0, 1], 'Invalid')
            ->inList('super', [0, 1], 'Invalid');
    }
}
