<?php

declare(strict_types = 1);

namespace App\Domain\User\Data;

use Cake\Chronos\Chronos;
use Selective\ArrayReader\ArrayReader;

/**
 * Data Model.
 */
final class UsersessionData
{
    public const FIELDS = [
        'ses_userid',
        'ses_lastlogin',
        'ses_lastactive',
        'ses_expire',
    ];

    public int $userid;

    private Chronos $lastlogin;

    private Chronos $lastactive;

    private Chronos $expire;

    /**
     * The constructor.
     *
     * @param array $data The data
     */
    public function __construct(array $data = [])
    {
        $reader = new ArrayReader($data);

        $this->userid = $reader->findInt('ses_userid') ?? 0;
        $this->lastlogin = $reader->findChronos('ses_lastlogin') ?? new Chronos();
        $this->lastactive = $reader->findChronos('ses_lastactive') ?? new Chronos();
        $this->expire = $reader->findChronos('ses_expire') ?? (new Chronos())->addMinutes(60);
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
     * Get remained time in sec.
     *
     * @param string|null $timestamp The expire
     *
     * @return int
     */
    public function getRemainedTime(?string $timestamp = null): int
    {
        return $this->expire->diffInSeconds(new Chronos($timestamp ?? (new Chronos())->toDateTimeString()));
    }

    /**
     * The transformer.
     *
     * @return array The data
     */
    public function transform(): array
    {
        return [
            'ses_userid' => $this->userid,
            'ses_lastlogin' => $this->lastlogin->toDateTimeString(),
            'ses_lastactive' => $this->lastactive->toDateTimeString(),
            'ses_expire' => $this->expire->toDateTimeString(),
        ];
    }
}
