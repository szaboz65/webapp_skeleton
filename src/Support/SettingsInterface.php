<?php

declare(strict_types = 1);

namespace App\Support;

interface SettingsInterface
{
    /**
     * Settings interface.
     *
     * @param string $key The key
     *
     * @return mixed
     */
    public function get(string $key = '');
}
