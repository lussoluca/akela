<?php

namespace App\Core\Domain\Service;

use App\Core\Domain\Model\Address;
use App\Core\Domain\Model\Group;
use App\Core\Infrastructure\Persistence\Repository\GroupRepository;
use Symfony\Component\Uid\UuidV4;

class GroupImporterService
{
    public function __construct(
        private readonly GroupRepository $groupRepository
    ) {}

    /**
     * @param array<array<int, string>> $groups
     */
    public function processGroups(array $groups): array
    {
        foreach ($groups as $internalId => $rowData) {
            $group = new Group(
              name          : $rowData[1],
              codiceOrdinale: $rowData[2],
              iban          : $rowData[3],
              address       : new Address(
                countryCode: 'IT',
                administrativeArea: $rowData[7],
                locality: $rowData[5],
                postalCode: (string)$rowData[6],
                addressLine1: $rowData[4],
                locale: 'it'
              ),
              id            : new UuidV4($rowData[0])
            );
            $this->groupRepository->add($group);
            array_unshift($groups[$internalId], $groups[$internalId]);
        }

        return $groups;
    }
}
