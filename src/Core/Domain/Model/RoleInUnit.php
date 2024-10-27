<?php

namespace App\Core\Domain\Model;

use App\Core\Domain\Model\Enum\Role;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity]
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

    public function __construct(Role $role, Unit $unit, ?UuidV4 $id = null)
    {
        $this->role = $role;
        $this->unit = $unit;
        $this->id = $id ?: new UuidV4();
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
}
