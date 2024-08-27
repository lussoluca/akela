<?php

namespace App\Entity;

use App\Entity\Core\Traits\SoftDeleteableEntity;
use App\Entity\Core\Traits\TimestampableEntity;
use App\Repository\LeaderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: LeaderRepository::class)]
class Leader extends Person
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    #[Id]
    #[Column(type: 'uuid', unique: true)]
    private UuidV4 $id;

    /**
     * @var Collection<int, RoleInUnit> $rolesInUnits
     */
    #[ORM\ManyToMany(targetEntity: RoleInUnit::class, inversedBy: 'leaders')]
    private Collection $rolesInUnits;

    public function __construct()
    {
        parent::__construct();
        $this->id = new UuidV4();
        $this->rolesInUnits = new ArrayCollection();
    }

    public function getId(): UuidV4
    {
        return $this->id;
    }

    /**
     * @return Collection<int, RoleInUnit>
     */
    public function getRolesInUnits(): Collection
    {
        return $this->rolesInUnits;
    }

    public function addRolesInUnit(RoleInUnit $rolesInUnit): static
    {
        if (!$this->rolesInUnits->contains($rolesInUnit)) {
            $this->rolesInUnits->add($rolesInUnit);
        }

        return $this;
    }

    public function removeRolesInUnit(RoleInUnit $rolesInUnit): static
    {
        $this->rolesInUnits->removeElement($rolesInUnit);

        return $this;
    }
}
