<?php

declare(strict_types = 1);

namespace App\Domain\Session\Service;

use App\Support\Validation\ValidationException;
use Cake\Validation\Validator;

/**
 * Service.
 */
final class ResetPasswordValidator
{
    /**
     * Validate login data.
     *
     * @param array $data The data
     *
     * @throws ValidationException
     *
     * @return void
     */
    public function validateLogin(array $data): void
    {
        $validator = $this->createValidator();
        $errors = $validator->validate($data);

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
        $validator = new Validator();

        return $validator
            ->requirePresence('pwemail')
            ->requirePresence('reset_code')
            ->requirePresence('pw')
            ->notEmptyString('email', 'Input required.')
            ->notEmptyString('reset_code', 'Input required.')
            ->notEmptyString('pw', 'Input required.')
            ->email('pwemail', false, 'Email required.')
            ->uuid('reset_code', 'Code required.')
            ->lengthBetween('pw', [8, 40], 'Password need in range [8, 40] character');
    }
}
