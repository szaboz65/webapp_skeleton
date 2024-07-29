<?php

declare(strict_types = 1);

namespace App\Domain\DBUpdate\Data;

use Selective\ArrayReader\ArrayReader;

/**
 * Data Model.
 */
final class DBUpdateData
{
    public ?int $id = null;

    public ?string $version = null;

    public ?string $description = null;

    public ?string $releasedate = null;

    public ?string $updatedate = null;

    /**
     * The constructor.
     *
     * @param array $data The data
     */
    public function __construct(array $data = [])
    {
        $reader = new ArrayReader($data);

        $this->id = $reader->findInt('up_id');
        $this->version = $reader->findString('up_version');
        $this->description = $reader->findString('up_description');
        $this->releasedate = $reader->findString('up_releasedate');
        $this->updatedate = $reader->findString('up_updatedate');
    }

    /**
     * The transformer.
     *
     * @return array The data
     */
    public function transform(): array
    {
        $array = [
            'up_version' => $this->version,
            'up_description' => $this->description,
            'up_releasedate' => $this->releasedate,
            'up_updatedate' => $this->updatedate,
        ];
        if (isset($this->id)) {
            $array['up_id'] = $this->id;
        }

        return $array;
    }
}
