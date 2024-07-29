<?php

namespace App\Test\Fixture;

/**
 * Fixture.
 */
class UserfailFixture
{
    public string $table = 'userfail';

    public array $records = [
        [
            'fail_userid' => 1,
            'fail_occured' => '2024-04-01 10:00:00',
        ],
        [
            'fail_userid' => 1,
            'fail_occured' => '2024-04-01 10:00:30',
        ],
        [
            'fail_userid' => 1,
            'fail_occured' => '2024-04-01 10:01:00',
        ],
        [
            'fail_userid' => 1,
            'fail_occured' => '2024-04-01 10:01:30',
        ],
        [
            'fail_userid' => 1,
            'fail_occured' => '2024-04-01 10:02:30',
        ],
    ];
}
