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
use Gedmo\Mapping\Annotation as Gedmo;
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

    /**
     * @Gedmo\Slug(fields={"name"})
     */
    #[Column(length: 128)]
    private string $slug; // @phpstan-ignore-line

    #[ManyToOne(targetEntity: 'App\Core\Domain\Model\Group', inversedBy: 'units')]
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
