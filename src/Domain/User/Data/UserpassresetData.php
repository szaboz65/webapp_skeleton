<?php

declare(strict_types = 1);

namespace App\Domain\User\Data;

use Cake\Chronos\Chronos;
use Selective\ArrayReader\ArrayReader;

/**
 * Data Model.
 */
final class UserpassresetData
{
    public const FIELDS = [
        'userid',
        'created',
        'expire',
        'reset_code',
    ];

    public int $userid;

    public Chronos $expire;

    public string $reset_code;

    /**
     * The constructor.
     *
     * @param array $data The data
     */
    public function __construct(array $data = [])
    {
        $reader = new ArrayReader($data);

        $this->userid = $reader->findInt('userid') ?? 0;
        $this->expire = $reader->findChronos('expire') ?? new Chronos();
        $this->reset_code = $reader->findString('reset_code') ?? '';
    }

    /**
     * Is session expired.
     *
     * @param string|null $timestamp The expire
     *
     * @return bool
     */
    public function isExpired(?string $timestamp = null): bool
    {
        return $this->expire->lessThan(new Chronos($timestamp ?? (new Chronos())->toDateTimeString()));
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
            'expire' => $this->expire->toDateTimeString(),
            'reset_code' => $this->reset_code,
        ];
    }
}
