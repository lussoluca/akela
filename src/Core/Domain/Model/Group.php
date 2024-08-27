<?php

declare(strict_types=1);

namespace App\Core\Domain\Model;

use App\Core\Domain\Model\Traits\CollectionsTrait;
use App\Core\Domain\Model\Traits\SoftDeleteableEntity;
use App\Core\Domain\Model\Traits\TimestampableEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Uid\UuidV4;

/**
 * Class Organization.
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
#[Entity]
class Group implements OrganizationInterface
{
    use TimestampableEntity;
    use SoftDeleteableEntity;
    use CollectionsTrait;

    #[Id]
    #[Column(type: 'uuid', unique: true)]
    private UuidV4 $id;

    #[Column(type: 'string', length: 255)]
    private string $name;

    #[Column(type: 'text')]
    private string $description;

    /**
     * @var \Doctrine\Common\Collections\Collection<int,\App\Core\Domain\Model\Unit>
     */
    #[OneToMany(targetEntity: 'App\Core\Domain\Model\Unit', mappedBy: 'group')]
    private Collection $units;

    /**
     * Organization constructor.
     */
    public function __construct(
        string $name,
        string $description,
    ) {
        $this->id = new UuidV4();
        $this->name = $name;
        $this->description = $description;
        $this->units = new ArrayCollection();
    }

    public function getId(): UuidV4
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function update(
        string $name,
        string $description,
    ): self {
        $this->name = $name;
        $this->description = $description;

        return $this;
    }

    public function equal(OrganizationInterface $other): bool
    {
        return $this->getId() === $other->getId();
    }

    public function isVisible(): bool
    {
        return !$this->isDeleted();
    }
}
