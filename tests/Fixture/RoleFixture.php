<?php

namespace App\Test\Fixture;

/**
 * Fixture.
 */
class RoleFixture
{
    public string $table = 'role';

    public array $records = [
        [
            'roleid' => 1,
            'rolename' => 'Admin',
        ],
        [
            'roleid' => 2,
            'rolename' => 'Other',
        ],
    ];
}
