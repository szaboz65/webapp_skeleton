<?php

namespace App\Test\Fixture;

/**
 * Fixture.
 */
class UsertypeFixture
{
    public string $table = 'usertype';

    public array $records = [
        [
            'utypeid' => 1,
            'utypename' => 'Admin',
            'roles' => 3,
        ],
        [
            'utypeid' => 2,
            'utypename' => 'Other',
            'roles' => 2,
        ],
    ];
}
