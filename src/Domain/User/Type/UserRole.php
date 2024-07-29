<?php

declare(strict_types = 1);

namespace App\Domain\User\Type;

/**
 * Type.
 */
final class UserRole
{
    /** @var int */
    public const ROLE_ADMIN = 1;

    /** @var int */
    public const ROLE_OTHER = 2;
}
