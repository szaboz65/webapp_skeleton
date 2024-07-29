<?php

declare(strict_types = 1);

namespace App\Domain\User\Data;

use Selective\ArrayReader\ArrayReader;

/**
 * Data Model.
 */
final class UsertypeData
{
    public const FIELDS = [
        'utypeid',
        'utypename',
        'roles',
    ];

    public ?int $utypeid = null;

    public ?string $utypename = null;

    public ?int $roles = 0;

    /**
     * The constructor.
     *
     * @param array $data The data
     */
    public function __construct(array $data = [])
    {
        $reader = new ArrayReader($data);

        $this->utypeid = $reader->findInt('utypeid');
        $this->utypename = $reader->findString('utypename');
        $this->roles = $reader->findInt('roles');
    }

    /**
     * The transformer.
     *
     * @return array The data
     */
    public function transform(): array
    {
        return [
            'utypeid' => $this->utypeid,
            'utypename' => $this->utypename,
            'roles' => $this->roles,
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
            'id' => $this->utypeid,
            'text' => $this->utypename,
        ];
    }
}
