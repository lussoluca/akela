<?php

namespace App\Core\Domain\Model;

use App\Core\Domain\Model\Enum\UnitType;
use App\Core\Domain\Model\Traits\CollectionsTrait;
use App\Core\Domain\Model\Traits\SoftDeleteableEntity;
use App\Core\Domain\Model\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Uid\UuidV4;

#[Entity]
class Unit implements UnitInterface
{
    use TimestampableEntity;
    use SoftDeleteableEntity;
    use CollectionsTrait;

    #[Id]
    #[Column(type: 'uuid')]
    protected UuidV4 $id;

    #[Column(type: 'string', length: 255)]
    protected string $name;

    #[ManyToOne(targetEntity: Group::class, inversedBy: 'units')]
    #[JoinColumn(name: 'group_id', referencedColumnName: 'id')]
    protected GroupInterface $group;

    #[Column(enumType: UnitType::class)]
    protected ?UnitType $type = null;

    public function __construct(
        string $name,
        UnitType $type,
        GroupInterface $group,
        ?UuidV4 $id = null,
    ) {
        $this->name = $name;
        $this->group = $group;
        $this->type = $type;
        $this->id = $id ?: new UuidV4();
    }

    public function getGroup(): GroupInterface
    {
        return $this->group;
    }

    public function getId(): UuidV4
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): ?UnitType
    {
        return $this->type;
    }

    public function setType(UnitType $type): static
    {
        $this->type = $type;

        return $this;
    }
}
