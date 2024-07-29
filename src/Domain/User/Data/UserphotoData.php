<?php

declare(strict_types = 1);

namespace App\Domain\User\Data;

use Selective\ArrayReader\ArrayReader;

/**
 * Data Model.
 */
final class UserphotoData
{
    public const FIELDS = [
        'userid',
        'photo',
    ];

    public ?int $userid = null;

    public ?string $photo = null;

    /**
     * The constructor.
     *
     * @param array $data The data
     */
    public function __construct(array $data = [])
    {
        $reader = new ArrayReader($data);

        $this->userid = $reader->findInt('userid');
        $this->photo = $reader->findString('photo');
    }

    /**
     * The transformer.
     *
     * @return array The data
     */
    public function transform(): array
    {
        return [
            'userid' => $this->userid,
            'photo' => $this->photo,
        ];
    }
}
