<?php

namespace App\Core\Domain\Service;

use App\Core\Domain\Model\Address;
use App\Core\Domain\Model\Group;
use App\Core\Domain\Model\GroupInterface;
use App\Core\Infrastructure\Persistence\Repository\GroupRepository;
use Symfony\Component\Uid\UuidV4;

class GroupImporterService
{
    public function __construct(
        private readonly GroupRepository $groupRepository
    ) {}

    /**
     * @param array<int, array<int, bool|int|string>> $groups
     */
    public function processGroups(array $groups): void
    {
        foreach ($groups as $rowData) {
            $uuid = (string) $rowData[0];

            /** @var ?GroupInterface $group */
            $group = $this->groupRepository->find($uuid);
            if (null !== $group) {
                continue;
            }
            $group = new Group(
                name: (string) $rowData[1],
                codiceOrdinale: (string) $rowData[2],
                iban: (string) $rowData[3],
                address: new Address(
                    countryCode: 'IT',
                    administrativeArea: (string) $rowData[7],
                    locality: (string) $rowData[5],
                    postalCode: (string) $rowData[6],
                    addressLine1: (string) $rowData[4],
                    locale: 'it'
                ),
                id: new UuidV4($uuid)
            );
            $this->groupRepository->add($group);
        }
    }
}
