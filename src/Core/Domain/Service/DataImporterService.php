<?php

declare(strict_types=1);

namespace App\Core\Domain\Service;

use App\Core\Domain\Model\Traits\LoggerUnawareTrait;
use App\Core\Domain\Model\Traits\OverwritableTrait;
use OpenSpout\Reader\XLSX\Reader;
use Symfony\Component\Filesystem\Filesystem;

class DataImporterService
{
    use OverwritableTrait;
    use LoggerUnawareTrait;

    public const string EXECL_WORKSHEET_GROUPS = 'gruppo';
    public const string EXECL_WORKSHEET_UNITS = 'unità';
    public const string EXECL_WORKSHEET_PROFILES = 'profili';
    public const string EXECL_WORKSHEET_LEADERS = 'leader';
    public const string EXECL_WORKSHEET_SCOUTS = 'scout';

    public function __construct(
        private GroupImporterService $groupImporterService,
        private UnitImporterService $unitImporterService,
        private ProfileImporterService $profileImporterService,
        private LeaderImporterService $leaderImporterService,
        private ScoutImporterService $scoutImporterService,
    ) {}

    /**
     * @throws \Exception
     */
    public function import(string $fileToImportPath): void
    {
        try {
            $this->logInfo('Opening file: '.$fileToImportPath);
            $fileSystem = new Filesystem();
            if (!$fileSystem->exists([$fileToImportPath])) {
                throw new \Exception('File not found: '.$fileToImportPath);
            }
            $reader = new Reader();
            $reader->open($fileToImportPath);

            $rowsData = [];
            foreach ($reader->getSheetIterator() as $sheet) {
                // only read data from "summary" sheet
                $i = 0;
                foreach ($sheet->getRowIterator() as $row) {
                    if (0 == $i) {
                        ++$i;

                        continue;
                    }

                    $rowData = [];
                    foreach ($row->getCells() as $cell) {
                        /** @var bool|int|string $value */
                        $value = $cell->getValue();

                        $rowData[] = $value;
                    }
                    $rowsData[] = $rowData;
                }

                if (!empty($rowData) && self::EXECL_WORKSHEET_GROUPS === strtolower($sheet->getName())) {
                    $this->logInfo('Processing groups');
                    $this->groupImporterService->setOverwrite($this->isOverwritable());
                    $this->groupImporterService->setLogger($this->logger);
                    $this->groupImporterService->processGroups($rowsData);
                }
                if (!empty($rowData) && self::EXECL_WORKSHEET_UNITS === strtolower($sheet->getName())) {
                    $this->logInfo('Processing units');
                    $this->unitImporterService->setOverwrite($this->isOverwritable());
                    $this->unitImporterService->setLogger($this->logger);
                    $this->unitImporterService->processUnits($rowsData);
                }
                if (!empty($rowData) && self::EXECL_WORKSHEET_PROFILES === strtolower($sheet->getName())) {
                    $this->logInfo('Processing profiles');
                    $this->profileImporterService->setOverwrite($this->isOverwritable());
                    $this->profileImporterService->setLogger($this->logger);
                    $this->profileImporterService->processProfiles($rowsData);
                }
                if (!empty($rowData) && self::EXECL_WORKSHEET_LEADERS === strtolower($sheet->getName())) {
                    $this->logInfo('Processing leaders');
                    $this->leaderImporterService->setOverwrite($this->isOverwritable());
                    $this->leaderImporterService->setLogger($this->logger);
                    $this->leaderImporterService->processLeaders($rowsData);
                }
                if (!empty($rowData) && self::EXECL_WORKSHEET_SCOUTS === strtolower($sheet->getName())) {
                    $this->logInfo('Processing scouts');
                    $this->scoutImporterService->setOverwrite($this->isOverwritable());
                    $this->scoutImporterService->setLogger($this->logger);
                    $this->scoutImporterService->processScouts($rowsData);
                }
            }
            $this->logInfo('Import complete');
            $reader->close();
        } catch (\Throwable $e) {
            throw new \Exception('Exception at line '.$e->getLine().' [file: '.$e->getFile().'] :'.$e->getMessage(), $e->getCode());
        }
    }
}
