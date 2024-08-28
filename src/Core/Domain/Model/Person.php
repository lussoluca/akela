<?php

namespace App\Core\Domain\Model;

use App\Medical\Domain\Model\MedicalData;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\OneToOne;
use Symfony\Component\Uid\UuidV4;

#[Entity]
#[InheritanceType(value: 'SINGLE_TABLE')]
#[DiscriminatorColumn(name: 'discr', type: 'string')]
#[DiscriminatorMap(value: ['leader' => 'App\Core\Domain\Model\Leader', 'scout' => 'App\Core\Domain\Model\Scout'])]
class Person
{
    #[Id]
    #[Column(type: 'uuid', unique: true)]
    protected UuidV4 $id;

    #[OneToOne(inversedBy: 'person', cascade: ['persist', 'remove'])]
    private ?MedicalData $medicalData = null;

    public function getId(): ?UuidV4
    {
        return $this->id;
    }

    public function getMedicalData(): ?MedicalData
    {
        return $this->medicalData;
    }

    public function setMedicalData(?MedicalData $medicalData): static
    {
        $this->medicalData = $medicalData;

        return $this;
    }
}
