<?php

declare(strict_types = 1);

namespace App\Support\W2;

/**
 * Sort information for finder request.
 */
final class Sort
{
    public const ASC = 0;

    public const DESC = 1;

    public string $field;

    public int $direction;

    /**
     * Constructor.
     *
     * @param array $data The data
     */
    public function __construct(array $data)
    {
        $this->field = $data['field'] ?? '';
        $this->direction = $data['direction'] ?? self::ASC;
    }
}
