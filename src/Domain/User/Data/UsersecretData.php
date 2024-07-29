<?php

declare(strict_types = 1);

namespace App\Domain\User\Data;

use Cake\Chronos\Chronos;
use Selective\ArrayReader\ArrayReader;

/**
 * Data Model.
 */
final class UsersecretData
{
    public const FIELDS = [
        'userid',
        'expire',
        'secret',
        'inactive',
    ];

    public ?int $userid = null;

    private ?Chronos $expire = null;

    private ?string $secret = null;

    private ?int $inactive = null;

    /**
     * The constructor.
     *
     * @param array $data The data
     */
    public function __construct(array $data = [])
    {
        $reader = new ArrayReader($data);

        $this->userid = $reader->findInt('userid');
        $this->expire = $reader->findChronos('expire');
        $this->secret = $reader->findString('secret');
        $this->inactive = $reader->findInt('secret');
    }

    /**
     * Set the expire.
     *
     * @param string $timestamp The expire
     *
     * @return void
     */
    public function setExpire(string $timestamp): void
    {
        $this->expire = new Chronos($timestamp);
    }

    /**
     * Set the secret.
     *
     * @param string $secret The secret
     *
     * @return void
     */
    public function setSecret(string $secret): void
    {
        $this->secret = (string)password_hash($secret, PASSWORD_DEFAULT);
    }

    /**
     * Verify secret.
     *
     * @param string $secret The secret
     *
     * @return bool
     */
    public function verifySecret(string $secret): bool
    {
        return password_verify($secret, (string)$this->secret);
    }

    /**
     * Set the inactive.
     *
     * @param bool $inactive Is inactive
     *
     * @return void
     */
    public function setInactive(bool $inactive): void
    {
        $this->inactive = $inactive ? 1 : 0;
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
            'expire' => $this->expire ? $this->expire->toDateTimeString() : (new Chronos())->toDateTimeString(),
            'secret' => $this->secret,
            'inactive' => $this->inactive,
        ];
    }
}
