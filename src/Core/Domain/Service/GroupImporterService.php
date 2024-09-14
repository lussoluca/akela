<?php

namespace App\Core\Domain\Service;

use App\Core\Domain\Model\Group;
use App\Core\Infrastructure\Persistence\Repository\GroupRepository;

class GroupImporterService
{
    public function __construct(
        private GroupRepository $groupRepository
    ) {}

    /**
     * @param array<array<int, string>> $groups
     */
    public function processGroups(array $groups): array
    {
        foreach ($groups as $internalId => $rowData) {
            $group = new Group($rowData[1], $rowData[1]);
            $this->groupRepository->add($group);
            array_unshift($groups[$internalId], $groups[$internalId]);
        }
        return $groups;
    }
}
