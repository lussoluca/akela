<?php

declare(strict_types=1);

namespace App\Core\Domain\Command;

class ImportData
{
    public function __construct(
        private string $filePath,
    ) {}

    public function getFilePath(): string
    {
        return $this->filePath;
    }
}
