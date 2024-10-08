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
    private UuidV4 $id;

    #[Column(type: 'string', length: 255)]
    private string $name;

    #[ManyToOne(targetEntity: 'App\Core\Domain\Model\Group', inversedBy: 'units')]
    #[JoinColumn(name: 'group_id', referencedColumnName: 'id')]
    private GroupInterface $group;

    #[Column(enumType: UnitType::class)]
    private ?UnitType $type = null;

    public function __construct(
        string $name,
        GroupInterface $group,
    ) {
        $this->id = new UuidV4();
        $this->name = $name;
        $this->group = $group;
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
