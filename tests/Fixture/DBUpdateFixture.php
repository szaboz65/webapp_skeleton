<?php

declare(strict_types = 1);

namespace App\Test\Fixture;

/**
 * Fixture.
 */
class DBUpdateFixture
{
    public string $table = 'dbupdate';

    public array $record = [
        'up_id' => 1,
        'up_version' => '0001',
        'up_description' => 'description',
        'up_releasedate' => '2024-03-25 13:24:36',
        'up_updatedate' => '2024-03-25 14:28:54',
    ];
}
