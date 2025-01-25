<?php

namespace App\Medical\Domain\Model;

use App\Core\Domain\Model\Person;
use App\Core\Domain\Model\Traits\SoftDeleteableEntity;
use App\Core\Domain\Model\Traits\TimestampableEntity;
use App\Medical\Infrastructure\Persistence\Repository\MedicalDataRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: MedicalDataRepository::class)]
class MedicalData
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    #[Id]
    #[Column(type: 'uuid', unique: true)]
    private UuidV4 $id;

    #[ORM\OneToOne(mappedBy: 'medicalData', cascade: ['persist', 'remove'])]
    private ?Person $person = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $birthday = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $birthProv = null;

    #[ORM\Column(length: 100)]
    private ?string $birthCity = null;

    #[ORM\Column(length: 16)]
    private ?string $fiscalCode = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $healthCardNumber = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $parentsAvailability = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $seriousPathologies = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $surgery = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $medicines = null;

    #[ORM\Column]
    private ?bool $menarche = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $intolerancesAndAllergies = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $howToIntervene = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $dietLimitations = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $additionalInfo = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $uploadedAt = null;

    public function __construct()
    {
        $this->id = new UuidV4();
    }

    public function getId(): ?UuidV4
    {
        return $this->id;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): static
    {
        // unset the owning side of the relation if necessary
        if (null === $person && null !== $this->person) {
            $this->person->setMedicalData(null);
        }

        // set the owning side of the relation if necessary
        if (null !== $person && $person->getMedicalData() !== $this) {
            $person->setMedicalData($this);
        }

        $this->person = $person;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): static
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getBirthProv(): ?string
    {
        return $this->birthProv;
    }

    public function setBirthProv(?string $birthProv): static
    {
        $this->birthProv = $birthProv;

        return $this;
    }

    public function getBirthCity(): ?string
    {
        return $this->birthCity;
    }

    public function setBirthCity(string $birthCity): static
    {
        $this->birthCity = $birthCity;

        return $this;
    }

    public function getFiscalCode(): ?string
    {
        return $this->fiscalCode;
    }

    public function setFiscalCode(string $fiscalCode): static
    {
        $this->fiscalCode = $fiscalCode;

        return $this;
    }

    public function getHealthCardNumber(): ?string
    {
        return $this->healthCardNumber;
    }

    public function setHealthCardNumber(?string $healthCardNumber): static
    {
        $this->healthCardNumber = $healthCardNumber;

        return $this;
    }

    public function getParentsAvailability(): ?string
    {
        return $this->parentsAvailability;
    }

    public function setParentsAvailability(?string $parentsAvailability): static
    {
        $this->parentsAvailability = $parentsAvailability;

        return $this;
    }

    public function getSeriousPathologies(): ?string
    {
        return $this->seriousPathologies;
    }

    public function setSeriousPathologies(?string $seriousPathologies): static
    {
        $this->seriousPathologies = $seriousPathologies;

        return $this;
    }

    public function getSurgery(): ?string
    {
        return $this->surgery;
    }

    public function setSurgery(?string $surgery): static
    {
        $this->surgery = $surgery;

        return $this;
    }

    public function getMedicines(): ?string
    {
        return $this->medicines;
    }

    public function setMedicines(?string $medicines): static
    {
        $this->medicines = $medicines;

        return $this;
    }

    public function isMenarche(): ?bool
    {
        return $this->menarche;
    }

    public function setMenarche(bool $menarche): static
    {
        $this->menarche = $menarche;

        return $this;
    }

    public function getIntolerancesAndAllergies(): ?string
    {
        return $this->intolerancesAndAllergies;
    }

    public function setIntolerancesAndAllergies(?string $intolerancesAndAllergies): static
    {
        $this->intolerancesAndAllergies = $intolerancesAndAllergies;

        return $this;
    }

    public function getHowToIntervene(): ?string
    {
        return $this->howToIntervene;
    }

    public function setHowToIntervene(?string $howToIntervene): static
    {
        $this->howToIntervene = $howToIntervene;

        return $this;
    }

    public function getDietLimitations(): ?string
    {
        return $this->dietLimitations;
    }

    public function setDietLimitations(?string $dietLimitations): static
    {
        $this->dietLimitations = $dietLimitations;

        return $this;
    }

    public function getAdditionalInfo(): ?string
    {
        return $this->additionalInfo;
    }

    public function setAdditionalInfo(?string $additionalInfo): static
    {
        $this->additionalInfo = $additionalInfo;

        return $this;
    }

    public function getUploadedAt(): ?\DateTimeImmutable
    {
        return $this->uploadedAt;
    }

    public function setUploadedAt(?\DateTimeImmutable $uploadedAt): static
    {
        $this->uploadedAt = $uploadedAt;

        return $this;
    }
}
