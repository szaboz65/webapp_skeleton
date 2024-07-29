<?php

declare(strict_types = 1);

namespace App\Domain\User\Data;

use Selective\ArrayReader\ArrayReader;

/**
 * Data Model.
 */
final class RoleData
{
    public const FIELDS = [
        'roleid',
        'rolename',
    ];

    public ?int $roleid = null;

    public ?string $rolename = null;

    /**
     * The constructor.
     *
     * @param array $data The data
     */
    public function __construct(array $data = [])
    {
        $reader = new ArrayReader($data);

        $this->roleid = $reader->findInt('roleid');
        $this->rolename = $reader->findString('rolename');
    }

    /**
     * The transformer.
     *
     * @return array The data
     */
    public function transform(): array
    {
        return [
            'roleid' => $this->roleid,
            'rolename' => $this->rolename,
        ];
    }

    /**
     * The transformer to item.
     *
     * @return array The data
     */
    public function transformItem(): array
    {
        return [
            'id' => $this->roleid,
            'text' => $this->rolename,
        ];
    }
}
