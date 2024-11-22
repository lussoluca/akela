<?php

namespace App\Core\Domain\Service;

use Psr\Log\LoggerInterface;
use App\Core\Domain\Exception\InvalidRoleException;
use App\Core\Domain\Model\Traits\LoggerUnawareTrait;
use App\Core\Domain\Exception\ProfileNotFountException;
use App\Core\Domain\Exception\UnitNotFoundException;
use App\Core\Domain\Model\Enum\Role;
use App\Core\Domain\Model\Leader;
use App\Core\Domain\Model\Profile;
use App\Core\Domain\Model\RoleInUnit;
use App\Core\Domain\Model\Traits\OverwritableTrait;
use App\Core\Infrastructure\Persistence\Repository\LeaderRepository;
use App\Core\Infrastructure\Persistence\Repository\ProfileRepository;
use App\Core\Infrastructure\Persistence\Repository\UnitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Uid\UuidV4;

class LeaderImporterService
{
    use OverwritableTrait, LoggerUnawareTrait;

    public function __construct(
        private readonly LeaderRepository  $leaderRepository,
        private readonly UnitRepository    $unitRepository,
        private readonly ProfileRepository $profileRepository,
    )
    {
    }

    /**
     * @param array<int, array<int, bool|int|string>> $leaders
     */
    public function processLeaders(array $leaders): void
    {
        foreach ($leaders as $rowData) {
            /**
             * @var array<int, bool|int|string> $rowData
             */
            $leader = $this->leaderRepository->find((string)$rowData[0]);

            $roleInUnit = null;
            $unit = $this->unitRepository->find((string)$rowData[1]);
            if (null !== $unit) {
                $role = Role::tryFrom((string)$rowData[3]);
                if (null === $role) {
                    throw new InvalidRoleException((string)$rowData[3]);
                }
                $roleInUnit = new RoleInUnit(
                    $role,
                    $unit,
                );
            } else {
                throw new UnitNotFoundException((string)$rowData[1]);
            }

            $profile = $this->profileRepository->find((string)$rowData[2]);
            if (null === $profile) {
                throw new ProfileNotFountException((string)$rowData[2]);
            }

            $rolesInUnits = new ArrayCollection();
            $rolesInUnits->add($roleInUnit);

            if ($this->isOverwritable()) {
                if (null !== $leader) {
                    $this->logInfo('Updating leader with id: ' . $rowData[0]);
                    $leader->update(
                        rolesInUnit: $rolesInUnits,
                        profile: $profile
                    );
                } else {
                    $leader = $this->createLeader($rolesInUnits, $profile, (string)$rowData[0]);
                }
            } else {
                if (null !== $leader) {
                    continue;
                }
                $leader = $this->createLeader($rolesInUnits, $profile, (string)$rowData[0]);
            }

            $this->leaderRepository->add($leader);
        }
    }

    /**
     * @param ArrayCollection<int, RoleInUnit> $rolesInUnits
     */
    public function createLeader(ArrayCollection $rolesInUnits, Profile $profile, string $uuid): Leader
    {
        $this->logInfo('Creating leader with id: ' . $uuid);
        return new Leader(
            rolesInUnit: $rolesInUnits,
            profile: $profile,
            id: new UuidV4($uuid)
        );
    }
}
