<?php

declare(strict_types=1);

namespace App\Core\Domain\Service;

use App\Core\Domain\Model\Group;
use App\Core\Infrastructure\Persistence\Repository\GroupRepository;
use OpenSpout\Reader\XLSX\Reader;
use Symfony\Component\Filesystem\Filesystem;

class DataImportService
{
    public const EXECL_WORKSHEET_GROUPS = 'gruppo';
    public const EXECL_WORKSHEET_UNITS = 'unità';
    public const EXECL_WORKSHEET_PROFILES = 'profili';
    public const EXECL_WORKSHEET_LEADERS = 'leader';
    public const EXECL_WORKSHEET_SCOUTS = 'scout';

    public function __construct(private GroupRepository $groupRepository) {}

    /**
     * @throws \Exception
     */
    public function import(string $fileToImportPath): void
    {
        $fileSystem = new Filesystem();
        if (!$fileSystem->exists([$fileToImportPath])) {
            throw new \Exception('File not found: '.$fileToImportPath);
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
            foreach ($sheet->getRowIterator() as $row) {
                $rowData = [];
                foreach ($row->getCells() as $cell) {
                    // do something with a cell for example print it.
                    $value = $cell->getValue();
                    if (!is_numeric($value)) {
                        break;
                    }
                    $rowData[] = $value;
                }
                if (self::EXECL_WORKSHEET_GROUPS === $sheet->getName()) {
                    $groups[$rowData[0]] = $rowData;
                }
            }
            if (!$groupsProcessed) {
                $this->processGroups($groups);
                $groupsProcessed = true;
            }
        }
        $reader->close();
    }

    /**
     * @param mixed[] $groups
     */
    protected function processGroups(array $groups): array
    {
        foreach ($groups as $internalId => $rowData) {
            $group = new Group($rowData[1], $rowData[1]);
            $this->groupRepository->add($group);
            $groups[$internalId][0] = $group->getId();
        }

        return $groups;
    }
}
