<?php

namespace App\Core\Domain\Service;

use App\Core\Domain\Model\Email;
use Symfony\Component\Uid\UuidV4;
use App\Core\Domain\Model\Profile;
use App\Core\Domain\Model\Address;
use App\Core\Domain\Model\Enum\Gender;
use App\Core\Domain\Model\Traits\OverwritableTrait;
use App\Core\Infrastructure\Persistence\Repository\ProfileRepository;

class ProfileImporterService
{
    use OverwritableTrait;

    public function __construct(
        private readonly ProfileRepository $profileRepository,
    )
    {
    }

    /**
     * @param array<int, array<int, bool|int|string>> $profiles
     */
    public function processProfiles(array $profiles): void
    {
        foreach ($profiles as $rowData) {
            $profile = $this->profileRepository->find((string)$rowData[0]);
            if (null !== $profile) {
                continue;
            }

            /** @var \DateTimeInterface $birthDate */
            $birthDate = $rowData[5];
            if (!$birthDate instanceof \DateTimeInterface) {
                throw new \RuntimeException('Invalid birth date');
            }
            $birthDate = (new \DateTime())->setTimestamp($birthDate->getTimestamp());


            if ($this->isOverwritable()) {
                if (null !== $profile) {
                    $profile->update(
                        firstname: (string)$rowData[1],
                        lastname: (string)$rowData[2],
                        birthAddress: new Address(
                            countryCode: 'IT',
                            administrativeArea: (string)$rowData[3],
                            locality: (string)$rowData[4],
                            postalCode: '',
                            addressLine1: '',
                            locale: 'it'
                        ),
                        birthDate: $birthDate,
                        fiscalCode: (string)$rowData[6],
                        email: (isset($rowData[9]) ? new Email((string)$rowData[9]) : new Email('')),
                        phone: (string)($rowData[8] ?? ''),
                        gender: Gender::UNDEFINED,
                    );
                } else {
                    $profile = $this->createGroup($rowData, $uuid);
                }
            } else {
                if (null !== $profile) {
                    continue;
                }
                $profile = $this->createProfile($rowData, $uuid);
            }
            $this->profileRepository->add($profile);
        }
    }

    /**
     * @param array $rowData
     * @param \DateTime $birthDate
     * @return array
     */
    public function createProfile(array $rowData, \DateTime $birthDate): array
    {
        $profile = new Profile(
            firstname: (string)$rowData[1],
            lastname: (string)$rowData[2],
            birthAddress: new Address(
                countryCode: 'IT',
                administrativeArea: (string)$rowData[3],
                locality: (string)$rowData[4],
                postalCode: '',
                addressLine1: '',
                locale: 'it'
            ),
            birthDate: $birthDate,
            fiscalCode: (string)$rowData[6],
            email: (isset($rowData[9]) ? new Email((string)$rowData[9]) : new Email('')),
            phone: (string)($rowData[8] ?? ''),
            gender: Gender::UNDEFINED,
            id: new UuidV4((string)$rowData[0]),
        );
        return $profile;
    }
}
