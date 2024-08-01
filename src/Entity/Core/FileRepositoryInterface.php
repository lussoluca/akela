<?php

declare(strict_types=1);

namespace App\Entity\Core;

/**
 * Interface FileRepositoryInterface.
 */
interface FileRepositoryInterface
{
    public function findOrNull(int $id): ?FileInterface;
}
