<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Id;
use App\Entity\Enum\UnitType;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Uid\UuidV4;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Core\Traits\CollectionsTrait;
use App\Entity\Core\Traits\TimestampableEntity;
use App\Entity\Core\Traits\SoftDeleteableEntity;

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

    /**
     * @Gedmo\Slug(fields={"name"})
     */
    #[Column(length: 128)]
    private string $slug;

    #[ManyToOne(targetEntity: 'App\Entity\Group', inversedBy: 'units')]
    #[JoinColumn(name: 'organization_id', referencedColumnName: 'id')]
    private OrganizationInterface $group;

    #[Column(enumType: UnitType::class)]
    private ?UnitType $type = null;

    public function __construct(
        string $name,
        OrganizationInterface $group,
    ) {
        $this->id = new UuidV4();
        $this->name = $name;
        $this->group = $group;
    }

    public function getGroup(): OrganizationInterface
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

    public function getSlug(): string
    {
        return $this->slug;
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
