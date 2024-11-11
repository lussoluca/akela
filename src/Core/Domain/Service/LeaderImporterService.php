<?php

namespace App\Core\Domain\Service;

use App\Core\Domain\Model\Enum\Role;
use App\Core\Domain\Model\Leader;
use App\Core\Domain\Model\RoleInUnit;
use App\Core\Domain\Model\Traits\OverwritableTrait;
use App\Core\Infrastructure\Persistence\Repository\LeaderRepository;
use App\Core\Infrastructure\Persistence\Repository\ProfileRepository;
use App\Core\Infrastructure\Persistence\Repository\UnitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Uid\UuidV4;

class LeaderImporterService
{
    use OverwritableTrait;

    public function __construct(
        private readonly LeaderRepository $leaderRepository,
        private readonly UnitRepository $unitRepository,
        private readonly ProfileRepository $profileRepository,
    ) {}

    /**
     * @param array<int, array<int, bool|int|string>> $leaders
     */
    public function processLeaders(array $leaders): void
    {
        foreach ($leaders as $rowData) {
            $leader = $this->leaderRepository->find((string) $rowData[0]);
            if (null !== $leader) {
                continue;
            }

            $roleInUnit = null;
            $unit = $this->unitRepository->find((string) $rowData[1]);
            if (null !== $unit) {
                $roleInUnit = new RoleInUnit(
                    Role::from((string) $rowData[3]),
                    $unit,
                );
            } else {
                throw new \Exception('Unknown unit with id: '.$rowData[1]);
            }

            $profile = $this->profileRepository->find((string) $rowData[2]);
            if (null === $profile) {
                throw new \Exception('Unknown profile with id: '.$rowData[2]);
            }

            $rolesInUnits = new ArrayCollection();
            $rolesInUnits->add($roleInUnit);

            $leader = new Leader(
                rolesInUnit: $rolesInUnits,
                profile: $profile,
                id: new UuidV4((string) $rowData[0])
            );
            $this->leaderRepository->add($leader);
        }
    }
}
