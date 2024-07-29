<?php

declare(strict_types = 1);

namespace App\Domain\User\Data;

use Selective\ArrayReader\ArrayReader;

/**
 * Data Model.
 */
final class UserData
{
    public const FIELDS = [
        'userid',
        'name',
        'title',
        'phone',
        'email',
        'fk_utypeid',
        'inactive',
        'super',
    ];

    public ?int $userid = null;

    public ?int $fk_utypeid = null;

    public ?string $name = null;

    public ?string $phone = null;

    public ?string $title = null;

    public ?string $email = null;

    public ?bool $inactive = null;

    public ?bool $super = null;

    /**
     * The constructor.
     *
     * @param array $data The data
     */
    public function __construct(array $data = [])
    {
        $reader = new ArrayReader($data);

        $this->userid = $reader->findInt('userid');
        $this->fk_utypeid = $reader->findInt('fk_utypeid');
        $this->name = $reader->findString('name');
        $this->phone = $reader->findString('phone');
        $this->title = $reader->findString('title');
        $this->email = $reader->findString('email');
        $this->inactive = $reader->findBool('inactive');
        $this->super = $reader->findBool('super');
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
            'fk_utypeid' => $this->fk_utypeid,
            'name' => $this->name,
            'phone' => $this->phone,
            'title' => $this->title,
            'email' => $this->email,
            'inactive' => $this->inactive ? 1 : 0,
            'super' => $this->super ? 1 : 0,
        ];
    }
}
