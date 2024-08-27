<?php

namespace App\Entity;

use App\Entity\Enum\Role;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Symfony\Component\Uid\UuidV4;
use App\Repository\RoleInUnitRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: RoleInUnitRepository::class)]
class RoleInUnit
{
    #[Id]
    #[Column(type: 'uuid', unique: true)]
    private UuidV4 $id;

    #[Column(enumType: Role::class)]
    private ?Role $role = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Unit $unit = null;

    /**
     * @var Collection<int, Leader>
     */
    #[ORM\ManyToOne(targetEntity: Leader::class, inversedBy: 'roleInUnit')]
    private Collection $leaders;

    public function __construct()
    {
        $this->id = new UuidV4();
        $this->leaders = new ArrayCollection();
    }

    public function getId(): UuidV4
    {
        return $this->id;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(Role $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    public function setUnit(Unit $unit): static
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * @return Collection<int, Leader>
     */
    public function getLeaders(): Collection
    {
        return $this->leaders;
    }

    public function addLeader(Leader $leader): static
    {
        if (!$this->leaders->contains($leader)) {
            $this->leaders->add($leader);
            $leader->setRoleInUnit($this);
        }

        return $this;
    }

    public function removeLeader(Leader $leader): static
    {
        if ($this->leaders->removeElement($leader)) {
            // set the owning side to null (unless already changed)
            if ($leader->getRoleInUnit() === $this) {
                $leader->setRoleInUnit(null);
            }
        }

        return $this;
    }
}
