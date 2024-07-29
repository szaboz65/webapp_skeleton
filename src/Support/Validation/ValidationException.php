<?php

declare(strict_types = 1);

namespace App\Support\Validation;

/**
 * Validation exception.
 */
final class ValidationException extends \DomainException
{
    private array $errors;

    /**
     * Constructor.
     *
     * @param string $message The message
     * @param array $errors The errors
     * @param int $code The error code
     * @param \Throwable $previous The previous exception
     */
    public function __construct(
        string $message,
        array $errors = [],
        int $code = 422,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }

    /**
     * Get errors.
     *
     * @return array of errors
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
