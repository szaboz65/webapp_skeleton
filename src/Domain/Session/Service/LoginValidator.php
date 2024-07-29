<?php

declare(strict_types = 1);

namespace App\Domain\Session\Service;

use App\Support\Validation\ValidationException;
use Cake\Validation\Validator;

/**
 * Service.
 */
final class LoginValidator
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
            ->requirePresence('login')
            ->requirePresence('pass')
            ->notEmptyString('login', 'Input required.')
            ->email('login', false, 'Email required.')
            ->notEmptyString('pass', 'Input required.')
            ->minLength('pass', 8, 'Password is too sort.')
            ->maxLength('pass', 40, 'Password is too long.');
    }
}
