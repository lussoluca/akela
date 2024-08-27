<?php

declare(strict_types=1);

namespace App\Core\Domain\Model;

interface VisibleInterface
{
    /**
     * Return TRUE if the entity is visible.
     */
    public function isVisible(): bool;
}
