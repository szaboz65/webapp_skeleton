<?php

namespace App\Test\Fixture;

/**
 * Fixture.
 */
class UsersecretFixture
{
    public string $table = 'usersecret';

    public array $records = [
        [
            'userid' => 1,
            'expire' => '2024-01-01 00:00:00',
            'secret' => '$2y$10$gNzTOn0pbRmuFU4xQXVRteijglaZEqy85aRJuwV22tCkwuWHBS9Y6', // 'azAZ09!?'
            'inactive' => 1,
        ],
        [
            'userid' => 1,
            'expire' => '2025-01-01 00:00:00',
            'secret' => '$2y$10$QujxZt3/a2bE7moU1WVDXekKMTqG4dLsraMzfTttxDMgAq9owsr32',  // '23456789'
            'inactive' => 0,
        ],
    ];
}
