<?php

declare(strict_types = 1);

namespace App\Support\W2;

/**
 * Search information for finder request.
 */
final class Search
{
    public string $field;

    public int $type;

    public int $operator;

    /**
     * @var mixed
     */
    public $value;

    /**
     * Constructor.
     *
     * @param array $data The data
     */
    public function __construct(array $data)
    {
        $this->field = $data['field'] ?? '';
        $this->type = $data['type'] ?? Types::INT;
        $this->operator = $data['operator'] ?? Operators::EQ;
        $this->value = $data['value'] ?? 0;
    }
}
