<?php

declare(strict_types = 1);

namespace App\Domain\User\Data;

use Cake\Chronos\Chronos;
use Selective\ArrayReader\ArrayReader;

/**
 * Data Model.
 */
final class UserfailData
{
    public const FIELDS = [
        'fail_userid',
        'fail_occured',
    ];

    public int $userid;

    private Chronos $occured;

    /**
     * The constructor.
     *
     * @param array $data The data
     */
    public function __construct(array $data = [])
    {
        $reader = new ArrayReader($data);

        $this->userid = $reader->findInt('fail_userid') ?? 0;
        $this->occured = $reader->findChronos('fail_occured') ?? new Chronos();
    }

    /**
     * The transformer.
     *
     * @return array The data
     */
    public function transform(): array
    {
        return [
            'fail_userid' => $this->userid,
            'fail_occured' => $this->occured->toDateTimeString(),
        ];
    }
}
