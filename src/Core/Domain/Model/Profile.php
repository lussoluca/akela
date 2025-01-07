<?php

namespace App\Core\Domain\Model;

use App\Core\Domain\Model\Enum\Gender;
use App\Core\Domain\Model\Traits\SoftDeleteableEntity;
use App\Core\Domain\Model\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Id;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt')]
class Profile
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    #[Column(type: 'datetime', nullable: true)]
    protected ?\DateTime $birthDate = null;

    #[Id]
    #[Column(type: 'uuid', unique: true)]
    private UuidV4 $id;

    #[Column(length: 255)]
    private ?string $firstname = null;

    #[Column(length: 255)]
    private ?string $lastname = null;

    #[Column(length: 16, nullable: true)]
    private ?string $fiscalCode = null;

    #[Embedded(class: Address::class, columnPrefix: 'birth_address_')]
    private Address $birthAddress;

    #[Embedded(class: Email::class, columnPrefix: 'email_')]
    private EmailInterface $email;

    #[Column(length: 20, nullable: true)]
    private ?string $phone = null;

    #[Column(enumType: Gender::class)]
    private ?Gender $gender = null;

    public function __construct(
        string $firstname,
        string $lastname,
        Address $birthAddress,
        \DateTime $birthDate,
        string $fiscalCode,
        EmailInterface $email,
        string $phone,
        Gender $gender,
        ?UuidV4 $id = null,
    ) {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->birthAddress = $birthAddress;
        $this->birthDate = $birthDate;
        $this->fiscalCode = $fiscalCode;
        $this->email = $email;
        $this->phone = $phone;
        $this->gender = $gender;
        $this->id = $id ?: new UuidV4();
    }

    public function getId(): ?UuidV4
    {
        return $this->id;
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

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getGender(): ?Gender
    {
        return $this->gender;
    }

    public function setGender(?Gender $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getFiscalCode(): ?string
    {
        return $this->fiscalCode;
    }

    public function setFiscalCode(?string $fiscalCode): void
    {
        $this->fiscalCode = $fiscalCode;
    }

    public function getBirthAddress(): Address
    {
        return $this->birthAddress;
    }

    public function setBirthAddress(Address $birthAddress): void
    {
        $this->birthAddress = $birthAddress;
    }

    public function getBirthDate(): ?\DateTime
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTime $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    public function getEmail(): EmailInterface
    {
        return $this->email;
    }

    public function setEmail(EmailInterface $email): void
    {
        $this->email = $email;
    }

    public function update(
        string $firstname,
        string $lastname,
        Address $birthAddress,
        \DateTime $birthDate,
        string $fiscalCode,
        EmailInterface $email,
        string $phone,
        Gender $gender,
    ): self {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->birthAddress = $birthAddress;
        $this->birthDate = $birthDate;
        $this->fiscalCode = $fiscalCode;
        $this->email = $email;
        $this->phone = $phone;
        $this->gender = $gender;

        return $this;
    }

    public function equal(Profile $other): bool
    {
        return $this->getId() === $other->getId();
    }
}
