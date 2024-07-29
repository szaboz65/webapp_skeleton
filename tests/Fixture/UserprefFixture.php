<?php

namespace App\Test\Fixture;

/**
 * Fixture.
 */
class UserprefFixture
{
    public string $table = 'userpref';

    public array $records = [
        [
            'upref_id' => 1,
            'locale' => 'en-US',
            'schema' => 'normal',
        ],
        [
            'upref_id' => 2,
            'locale' => 'en-US',
            'schema' => 'normal',
        ],
    ];
}
