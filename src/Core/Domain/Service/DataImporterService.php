<?php

declare(strict_types=1);

namespace App\Core\Domain\Service;

use App\Core\Domain\Model\Traits\OverwritableTrait;
use OpenSpout\Reader\XLSX\Reader;
use Symfony\Component\Filesystem\Filesystem;

class DataImporterService
{
    use OverwritableTrait;

    public const string EXECL_WORKSHEET_GROUPS = 'gruppo';
    public const string EXECL_WORKSHEET_UNITS = 'unità';
    public const string EXECL_WORKSHEET_PROFILES = 'profili';
    public const string EXECL_WORKSHEET_LEADERS = 'leader';
    public const string EXECL_WORKSHEET_SCOUTS = 'scout';

    public function __construct(
        private readonly GroupImporterService   $groupImporterService,
        private readonly UnitImporterService    $unitImporterService,
        private readonly ProfileImporterService $profileImporterService,
        private readonly LeaderImporterService  $leaderImporterService,
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function import(string $fileToImportPath): void
    {
        try {
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
                    if (!empty($rowData) && self::EXECL_WORKSHEET_GROUPS === strtolower($sheet->getName())) {
                        $groups[] = $rowData;
                    }
                    if (!empty($rowData) && self::EXECL_WORKSHEET_UNITS === strtolower($sheet->getName())) {
                        $units[] = $rowData;
                    }
                    if (!empty($rowData) && self::EXECL_WORKSHEET_PROFILES === strtolower($sheet->getName())) {
                        $profiles[] = $rowData;
                    }
                    if (!empty($rowData) && self::EXECL_WORKSHEET_LEADERS === strtolower($sheet->getName())) {
                        $leaders[] = $rowData;
                    }
                }

                if (!$groupsProcessed) {
                    $this->groupImporterService->setOverwrite($this->isOverwritable());
                    $this->groupImporterService->processGroups($groups);
                    $groupsProcessed = true;

                    continue;
                }

                if (!$unitsProcessed) {
                    $this->unitImporterService->setOverwrite($this->isOverwritable());
                    $this->unitImporterService->processUnits($units);
                    $unitsProcessed = true;

                    continue;
                }

                if (!$profilesProcessed) {
                    $this->profileImporterService->setOverwrite($this->isOverwritable());
                    $this->profileImporterService->processProfiles($profiles);
                    $profilesProcessed = true;

                    continue;
                }

                if (!$leadersProcessed) {
                    $this->leaderImporterService->setOverwrite($this->isOverwritable());
                    $this->leaderImporterService->processLeaders($leaders);
                    $leadersProcessed = true;

                    // continue;
                }
            }
            $reader->close();
        } catch (\Throwable $e) {
            throw new \Exception('Exception at line ' . $e->getLine() . ' [file: ' . $e->getFile() . '] :' . $e->getMessage(), $e->getCode());
        }
    }
}
