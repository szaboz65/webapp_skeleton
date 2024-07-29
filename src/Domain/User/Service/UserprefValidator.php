<?php

declare(strict_types = 1);

namespace App\Domain\User\Service;

use App\Support\Validation\ValidationException;
use Cake\Validation\Validator;

/**
 * Service.
 */
final class UserprefValidator
{
    /**
     * Validate data and return with errors.
     *
     * @param array $data The data array
     *
     * @return array The errors
     */
    public function getValidateError(array $data): array
    {
        return $this->createValidator()->validate($data);
    }

    /**
     * Validate new userpref.
     *
     * @param array $data The data
     *
     * @throws ValidationException
     *
     * @return void
     */
    public function validateUserpref(array $data): void
    {
        $errors = $this->getValidateError($data);

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
            ->inList('locale', ['en-US', 'hu-HU'], 'Invalid')
            ->inList('schema', ['normal', 'dark'], 'Invalid');
    }
}
