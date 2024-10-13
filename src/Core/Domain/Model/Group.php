<?php

declare(strict_types=1);

namespace App\Core\Domain\Model;

use App\Core\Domain\Model\Traits\CollectionsTrait;
use App\Core\Domain\Model\Traits\SoftDeleteableEntity;
use App\Core\Domain\Model\Traits\TimestampableEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity]
#[ORM\Table(name: 'groups')]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt')]
class Group implements GroupInterface
{
    use TimestampableEntity;
    use SoftDeleteableEntity;
    use CollectionsTrait;

    #[Id]
    #[Column(type: 'uuid', unique: true)]
    private UuidV4 $id;

    #[Column(type: 'string', length: 255)]
    private string $name;

    #[Column(type: 'string', length: 5)]
    private string $codiceOrdinale;

    #[Column(type: 'string', length: 27)]
    private string $iban;

    #[Embedded(class: 'App\Core\Domain\Model\Address', columnPrefix: 'address_')]
    private Address $address;

    /**
     * @var \Doctrine\Common\Collections\Collection<int,\App\Core\Domain\Model\Unit>
     */
    #[OneToMany(targetEntity: 'App\Core\Domain\Model\Unit', mappedBy: 'group')]
    private Collection $units;

    /**
     * Group constructor.
     */
    public function __construct(
        string $name,
        string $codiceOrdinale,
        string $iban,
        Address $address,
        ?UuidV4 $id = null,
    ) {
        $this->id = $id ?: new UuidV4();
        $this->name = $name;
        $this->codiceOrdinale = $codiceOrdinale;
        $this->iban = $iban;
        $this->address = $address;
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

    public function getCodiceOrdinale(): string
    {
        return $this->codiceOrdinale;
    }

    public function getIban(): string
    {
        return $this->iban;
    }

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function getUnits(): Collection
    {
        return $this->units;
    }

    public function update(
        string $name,
        string $codiceOrdinale,
        string $iban,
        Address $address,
    ): self {
        $this->name = $name;
        $this->codiceOrdinale = $codiceOrdinale;
        $this->iban = $iban;
        $this->address = $address;

        return $this;
    }

    public function equal(GroupInterface $other): bool
    {
        return $this->getId() === $other->getId();
    }
}
