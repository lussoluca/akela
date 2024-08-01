<?php

declare(strict_types=1);

namespace App\Entity;

/**
 * Class DefaultSettings.
 */
class DefaultSettings extends Settings
{
    public function __construct()
    {
        parent::__construct(
            false,
            false,
            false,
            false,
            false
        );
    }
}
