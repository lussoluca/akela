<?php

declare(strict_types=1);

namespace App\Core\Domain\Command;

use App\Core\Domain\Service\DataImportService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ImportDataHandler
{
    public function __construct(
        private DataImportService $dataImportService,
        private LoggerInterface $logger,
    ) {}

    public function __invoke(ImportData $Import): void
    {
        try {
            $fileToImportPath = $Import->getFilePath();
            $this->dataImportService->import($fileToImportPath);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
