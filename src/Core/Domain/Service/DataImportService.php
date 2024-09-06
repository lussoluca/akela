<?php

declare(strict_types=1);

namespace App\Core\Domain\Service;

use Symfony\Component\Filesystem\Filesystem;

class DataImportService
{
    /**
     * @throws \Exception
     */
    public function import(string $fileToImportPath): void
    {
        $fileSystem = new Filesystem();
        if (!$fileSystem->exists([$fileToImportPath])) {
            throw new \Exception('File not found: '.$fileToImportPath);
        }
    }
}
