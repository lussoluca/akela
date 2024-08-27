<?php

namespace App\Entity;

use App\Entity\Core\Traits\SoftDeleteableEntity;
use App\Entity\Core\Traits\TimestampableEntity;
use App\Entity\Enum\Role;
use App\Repository\LeaderRepository;
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

    #[ORM\OneToMany(mappedBy: 'leaders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RoleInUnit $roleInUnit = null;

    public function __construct()
    {
        parent::__construct();
        $this->id = new UuidV4();
    }

    public function getId(): UuidV4
    {
        return $this->id;
    }

    public function getRoleInUnit(): ?RoleInUnit
    {
        return $this->roleInUnit;
    }

    public function setRoleInUnit(?RoleInUnit $roleInUnit): static
    {
        $this->roleInUnit = $roleInUnit;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->getRoleInUnit()?->getRole();
    }
}
