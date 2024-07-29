<?php

declare(strict_types = 1);

namespace App\Test\Traits;

/**
 * Fixture Trait.
 */
trait FixtureTrait
{
    protected array $fixtures = [
        \App\Test\Fixture\RoleFixture::class,
        \App\Test\Fixture\UsertypeFixture::class,
        \App\Test\Fixture\UserFixture::class,
        \App\Test\Fixture\UserprefFixture::class,
        \App\Test\Fixture\UserphotoFixture::class,
        \App\Test\Fixture\UsersecretFixture::class,
    ];
}
