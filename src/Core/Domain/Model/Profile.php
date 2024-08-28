<?php

namespace App\Core\Domain\Model;

use App\Core\Domain\Model\Enum\Gender;
use App\Core\Domain\Model\Traits\SoftDeleteableEntity;
use App\Core\Domain\Model\Traits\TimestampableEntity;
use App\Core\Infrastructure\Persistence\Repository\ProfileRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Uid\UuidV4;

/**
 * Class Leader.
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
#[ORM\Entity(repositoryClass: ProfileRepository::class)]
class Profile
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    #[Id]
    #[Column(type: 'uuid', unique: true)]
    private UuidV4 $id;

    #[Column(length: 255)]
    private ?string $firstname = null;

    #[Column(length: 255)]
    private ?string $lastname = null;

    #[Column(length: 20, nullable: true)]
    private ?string $phone = null;

    #[Column(enumType: Gender::class)]
    private ?Gender $gender = null;

    public function __construct()
    {
        $this->id = new UuidV4();
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
}
