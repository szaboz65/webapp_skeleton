<?php

namespace App\Test\Fixture;

use App\Domain\User\Type\UserType;

/**
 * Fixture.
 */
class UserFixture
{
    public string $table = 'user';

    public array $records = [
        [
            'userid' => 1,
            'fk_utypeid' => UserType::USERTYPE_ADMIN,
            'name' => 'Admin User',
            'phone' => '+123456789012',
            'title' => 'Admin',
            'email' => 'zoltan.szabo65@gmail.com',
            'inactive' => 0,
            'super' => 1,
        ],
        [
            'userid' => 2,
            'fk_utypeid' => UserType::USERTYPE_OTHER,
            'name' => 'Other User',
            'phone' => '+123456789012',
            'title' => 'Other',
            'email' => 'user.other@mail.com',
            'inactive' => 0,
            'super' => 0,
        ],
    ];
}
