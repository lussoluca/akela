<?php

declare(strict_types=1);

namespace App\Entity\Core;

/**
 * Interface FileInterface.
 */
interface FileInterface
{
    public function getId(): int;

    public function getPath(): string;

    public function getName(): string;

    public function getMimeType(): string;

    public function getSize(): string;

    public function isPersistent(): bool;
}
