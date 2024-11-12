<?php

namespace App\Core\Domain\Service;

use App\Core\Domain\Model\Enum\UnitType;
use App\Core\Domain\Model\GroupInterface;
use App\Core\Domain\Model\Traits\OverwritableTrait;
use App\Core\Domain\Model\Unit;
use App\Core\Infrastructure\Persistence\Repository\GroupRepository;
use App\Core\Infrastructure\Persistence\Repository\UnitRepository;
use Symfony\Component\Uid\UuidV4;

class UnitImporterService
{
    use OverwritableTrait;

    public function __construct(
        private readonly UnitRepository $unitRepository,
        private readonly GroupRepository $groupRepository
    ) {}

    /**
     * @param array<int, array<int, bool|int|string>> $units
     */
    public function processUnits(array $units): void
    {
        foreach ($units as $rowData) {
            $type = UnitType::tryFrom(strtolower((string) $rowData[3]));
            if (null === $type) {
                throw new \Exception('Unknown unit type: '.$rowData[3]);
            }

            /** @var ?GroupInterface $group */
            $group = $this->groupRepository->find((string) $rowData[1]);
            if (null === $group) {
                throw new \Exception('Unknown group with id: '.$rowData[1]);
            }

            $unit = $this->unitRepository->find((string) $rowData[0]);
            if ($this->isOverwritable()) {
                if (null !== $unit) {
                    $unit->update(name: (string) $rowData[2], type: $type);
                } else {
                    $unit = $this->createUnit($rowData, $type, $group);
                }
            } else {
                if (null !== $unit) {
                    continue;
                }
                $unit = $this->createUnit($rowData, $type, $group);
            }
            $this->unitRepository->add($unit);
        }
    }

    /**
     * @param array<int, bool|int|string> $rowData
     */
    public function createUnit(array $rowData, UnitType $type, GroupInterface $group): Unit
    {
        return new Unit(
            name: (string) $rowData[1],
            type: $type,
            group: $group,
            id: new UuidV4((string) $rowData[0])
        );
    }
}
