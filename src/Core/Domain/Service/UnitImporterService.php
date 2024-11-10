<?php

namespace App\Core\Domain\Service;

use App\Core\Domain\Model\Enum\UnitType;
use App\Core\Domain\Model\GroupInterface;
use App\Core\Domain\Model\Unit;
use App\Core\Infrastructure\Persistence\Repository\GroupRepository;
use App\Core\Infrastructure\Persistence\Repository\UnitRepository;
use Symfony\Component\Uid\UuidV4;

class UnitImporterService
{
    public bool $overwrite = false;

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

            /** @var GroupInterface $group */
            $group = $this->groupRepository->find((string) $rowData[1]);
            $unit = $this->unitRepository->find((string) $rowData[0]);
            if (null !== $unit) {
                continue;
            }

            $unit = new Unit(
                name: (string) $rowData[1],
                type: $type,
                group: $group,
                id: new UuidV4((string) $rowData[0])
            );
            $this->unitRepository->add($unit);
        }
    }
}
