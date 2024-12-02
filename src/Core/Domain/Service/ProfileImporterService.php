<?php

namespace App\Core\Domain\Service;

use App\Core\Domain\Model\Address;
use App\Core\Domain\Model\Email;
use App\Core\Domain\Model\Enum\Gender;
use App\Core\Domain\Model\Profile;
use App\Core\Domain\Model\Traits\LoggerUnawareTrait;
use App\Core\Domain\Model\Traits\OverwritableTrait;
use App\Core\Infrastructure\Persistence\Repository\ProfileRepository;
use Symfony\Component\Uid\UuidV4;

class ProfileImporterService
{
    use OverwritableTrait;
    use LoggerUnawareTrait;

    public function __construct(
        private readonly ProfileRepository $profileRepository,
    ) {}

    /**
     * @param array<int, array<int, bool|int|string>> $profiles
     */
    public function processProfiles(array $profiles): void
    {
        foreach ($profiles as $rowData) {
            /** @var array<int, bool|int|string> $rowData */
            $profile = $this->profileRepository->find((string) $rowData[0]);

            /** @var \DateTimeInterface $birthDate */
            $birthDate = $rowData[5];
            if (!$birthDate instanceof \DateTimeInterface) {
                throw new \RuntimeException('Invalid birth date');
            }
            $birthDate = (new \DateTime())->setTimestamp($birthDate->getTimestamp());

            if ($this->isOverwritable()) {
                if (null !== $profile) {
                    $this->logInfo('Creating profile with firstname: '.$rowData[1].' and lastname: '.$rowData[2]);
                    $profile->update(
                        firstname: (string) $rowData[1],
                        lastname: (string) $rowData[2],
                        birthAddress: new Address(
                            countryCode: 'IT',
                            administrativeArea: (string) $rowData[3],
                            locality: (string) $rowData[4],
                            postalCode: '',
                            addressLine1: '',
                            locale: 'it'
                        ),
                        birthDate: $birthDate,
                        fiscalCode: (string) $rowData[6],
                        email: (isset($rowData[9]) ? new Email((string) $rowData[9]) : new Email('')),
                        phone: (string) ($rowData[8] ?? ''),
                        gender: Gender::UNDEFINED,
                    );
                } else {
                    $profile = $this->createProfile($rowData, $birthDate);
                }
            } else {
                if (null !== $profile) {
                    continue;
                }
                $profile = $this->createProfile($rowData, $birthDate);
            }
            $this->profileRepository->add($profile);
        }
    }

    /**
     * @param array<int, bool|int|string> $rowData
     */
    public function createProfile(array $rowData, \DateTime $birthDate): Profile
    {
        $this->logInfo('Creating profile with firstname: '.$rowData[1].' and lastname: '.$rowData[2]);

        return new Profile(
            firstname: (string) $rowData[1],
            lastname: (string) $rowData[2],
            birthAddress: new Address(
                countryCode: 'IT',
                administrativeArea: (string) $rowData[3],
                locality: (string) $rowData[4],
                postalCode: '',
                addressLine1: '',
                locale: 'it'
            ),
            birthDate: $birthDate,
            fiscalCode: (string) $rowData[6],
            email: (isset($rowData[9]) ? new Email((string) $rowData[9]) : new Email('')),
            phone: (string) ($rowData[8] ?? ''),
            gender: Gender::UNDEFINED,
            id: new UuidV4((string) $rowData[0]),
        );
    }
}
