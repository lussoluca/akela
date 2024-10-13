<?php

declare(strict_types=1);

namespace App\Core\Domain\Service;

use OpenSpout\Reader\XLSX\Reader;
use Symfony\Component\Filesystem\Filesystem;

class DataImporterService
{
    public const EXECL_WORKSHEET_GROUPS = 'gruppo';
    public const EXECL_WORKSHEET_UNITS = 'unità';
    public const EXECL_WORKSHEET_PROFILES = 'profili';
    public const EXECL_WORKSHEET_LEADERS = 'leader';
    public const EXECL_WORKSHEET_SCOUTS = 'scout';

    public function __construct(
        private GroupImporterService $groupImporterService,
    ) {}

    /**
     * @throws \Exception
     */
    public function import(string $fileToImportPath): void
    {
        $fileSystem = new Filesystem();
        if (!$fileSystem->exists([$fileToImportPath])) {
            throw new \Exception('File not found: ' . $fileToImportPath);
        }

        $reader = new Reader();
        $reader->open($fileToImportPath);

        $groups = [];
        $groupsProcessed = false;
        $units = [];
        $unitsProcessed = false;
        $profiles = [];
        $profilesProcessed = false;
        $leaders = [];
        $leadersProcessed = false;
        $scouts = [];
        $scoutsProcessed = false;

        foreach ($reader->getSheetIterator() as $sheet) {
            // only read data from "summary" sheet
            $i = 0;
            foreach ($sheet->getRowIterator() as $row) {
                if ($i == 0) {
                    $i++;
                    continue;
                }

                $rowData = [];
                foreach ($row->getCells() as $cell) {
                    $value = $cell->getValue();

                    $rowData[] = $value;
                }
                if (!empty($rowData) && self::EXECL_WORKSHEET_GROUPS === $sheet->getName()) {
                    $groups[$rowData[0]] = $rowData;
                }
            }

            if (!$groupsProcessed) {
                // @var array<array<int, string>> $groups
                $this->groupImporterService->processGroups($groups);
                $groupsProcessed = true;
            }
        }
        $reader->close();
    }
}
