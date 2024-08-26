<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Symfony\Component\Uid\UuidV4;
use App\Repository\ScoutRepository;

#[ORM\Entity(repositoryClass: ScoutRepository::class)]
class Scout extends Person
{

    #[Id]
    #[Column(type: 'uuid', unique: true)]
    private UuidV4 $id;

    #[ORM\ManyToOne(inversedBy: 'scouts')]
    private ?Profile $parent1Profile = null;

    #[ORM\ManyToOne(inversedBy: 'scouts')]
    private ?Profile $parent2Profile = null;

    #[ORM\ManyToOne(inversedBy: 'scouts')]
    private ?Profile $ownProfile = null;

    public function __construct()
    {
        parent::__construct();
        $this->id = new UuidV4();
    }

    public function getId(): ?UuidV4
    {
        return $this->id;
    }

    public function getParent1Profile(): ?Profile
    {
        return $this->parent1Profile;
    }

    public function setParent1Profile(?Profile $parent1Profile): static
    {
        $this->parent1Profile = $parent1Profile;

        return $this;
    }

    public function getParent2Profile(): ?Profile
    {
        return $this->parent2Profile;
    }

    public function setParent2Profile(?Profile $parent2Profile): static
    {
        $this->parent2Profile = $parent2Profile;

        return $this;
    }

    public function getOwnProfile(): ?Profile
    {
        return $this->ownProfile;
    }

    public function setOwnProfile(?Profile $ownProfile): static
    {
        $this->ownProfile = $ownProfile;

        return $this;
    }
}
