<?php

declare(strict_types = 1);

namespace App\Domain\User\Data;

use Selective\ArrayReader\ArrayReader;

/**
 * Data Model.
 */
final class UserprefData
{
    public const FIELDS = [
        'upref_id',
        'locale',
        'schema',
    ];

    public ?int $upref_id = null;

    public ?string $locale = 'en-US';

    public ?string $schema = 'normal';

    /**
     * The constructor.
     *
     * @param array $data The data
     */
    public function __construct(array $data = [])
    {
        $reader = new ArrayReader($data);

        $this->upref_id = $reader->findInt('upref_id');
        $this->locale = $reader->findString('locale', 'en-US');
        $this->schema = $reader->findString('schema', 'normal');
    }

    /**
     * The transformer.
     *
     * @return array The data
     */
    public function transform(): array
    {
        return [
            'upref_id' => $this->upref_id,
            'locale' => $this->locale,
            'schema' => $this->schema,
        ];
    }
}
