<?php

namespace App\Entity;

use App\Entity\Core\Traits\SoftDeleteableEntity;
use App\Entity\Core\Traits\TimestampableEntity;
use App\Repository\MedicalDataRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
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
}
