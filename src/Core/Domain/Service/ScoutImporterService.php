<?php

namespace App\Core\Domain\Service;

use Psr\Log\LoggerInterface;
use App\Core\Domain\Model\Traits\LoggerUnawareTrait;
use App\Core\Domain\Exception\ProfileNotFountException;
use App\Core\Domain\Exception\UnitNotFoundException;
use App\Core\Domain\Model\Profile;
use App\Core\Domain\Model\Scout;
use App\Core\Domain\Model\Traits\OverwritableTrait;
use App\Core\Domain\Model\Unit;
use App\Core\Infrastructure\Persistence\Repository\ProfileRepository;
use App\Core\Infrastructure\Persistence\Repository\ScoutRepository;
use App\Core\Infrastructure\Persistence\Repository\UnitRepository;
use Symfony\Component\Uid\UuidV4;

class ScoutImporterService
{
    use OverwritableTrait, LoggerUnawareTrait;

    public function __construct(
        private readonly ScoutRepository   $scoutRepository,
        private readonly UnitRepository    $unitRepository,
        private readonly ProfileRepository $profileRepository,
    )
    {
    }

    /**
     * @param array<int, array<int, bool|int|string>> $scouts
     */
    public function processScouts(array $scouts): void
    {
        foreach ($scouts as $rowData) {
            $scout = $this->scoutRepository->find((string)$rowData[0]);

            $unit = $this->unitRepository->find((string)$rowData[1]);
            if (null === $unit) {
                throw new UnitNotFoundException((string)$rowData[1]);
            }

            $ownProfile = $this->profileRepository->find((string)$rowData[2]);
            if (null === $ownProfile) {
                throw new ProfileNotFountException((string)$rowData[2]);
            }

            $profile1 = $this->profileRepository->find((string)$rowData[3]);
            if (null === $profile1) {
                throw new ProfileNotFountException((string)$rowData[3]);
            }

            $profile2 = $this->profileRepository->find((string)$rowData[4]);
            if (null === $profile2) {
                throw new ProfileNotFountException((string)$rowData[4]);
            }

            $isAdult = (bool)$rowData[5];

            if ($this->isOverwritable()) {
                if (null !== $scout) {
                    $this->logInfo('Updating scout with id: ' . $rowData[0]);
                    $scout->update($unit, $ownProfile, $isAdult, $profile1, $profile2);
                } else {
                    $scout = $this->createScout($unit, $ownProfile, $profile1, $profile2, $isAdult, (string)$rowData[0]);
                }
            } else {
                if (null !== $scout) {
                    continue;
                }
                $scout = $this->createScout($unit, $ownProfile, $profile1, $profile2, $isAdult, (string)$rowData[0]);
            }

            $this->scoutRepository->add($scout);
        }
    }

    private function createScout(Unit $unit, Profile $ownProfile, ?Profile $profile1, ?Profile $profile2, bool $isAdult, string $uuid): Scout
    {
        $this->logInfo('Creating scout with id: ' . $uuid);
        return new Scout(
            $unit,
            $ownProfile,
            $isAdult,
            $profile1,
            $profile2,
            new UuidV4($uuid),
        );
    }
}
