<?php

namespace App\Domain\DBUpdate\Files;

use App\Domain\DBUpdate\Service\DBUpdateBase;

/**
 * Update file.
 */
class Update0001 extends DBUpdateBase
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(
            '0001',
            'Add dbupdate table',
            '2024-03-27 16:00:00'
        );
    }

    /**
     * {@inheritDoc}
     */
    public function doUpdate(): bool
    {
        return true;
    }
}
