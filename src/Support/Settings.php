<?php

declare(strict_types = 1);

namespace App\Support;

/**
 * The settings reader.
 */
class Settings implements SettingsInterface
{
    /**
     * @var array
     */
    private array $settings;

    /**
     * Settings constructor.
     *
     * @param array $settings The settings array
     */
    public function __construct(array $settings = [])
    {
        $this->settings = $settings;
    }

    /**
     * @param string $key The key
     *
     * @return mixed
     */
    public function get(string $key = '')
    {
        return (empty($key)) ? $this->settings : $this->settings[$key];
    }
}
