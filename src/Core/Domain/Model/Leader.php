<?php

namespace App\Core\Domain\Model;

use App\Core\Domain\Model\Traits\SoftDeleteableEntity;
use App\Core\Domain\Model\Traits\TimestampableEntity;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt')]
class Leader extends Person
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    /**
     * @var Collection<int, RoleInUnit> $rolesInUnits
     */
    #[ORM\ManyToMany(targetEntity: RoleInUnit::class, cascade: ['persist'])]
    protected Collection $rolesInUnits;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    protected ?Profile $profile = null;

    public function __construct(
        // @var Collection<int, App\Core\Domain\Model\RoleInUnit> $rolesInUnits
        Collection $rolesInUnit,
        ?Profile $profile = null,
        ?UuidV4 $id = null,
    ) {
        $this->rolesInUnits = $rolesInUnit;
        $this->profile = $profile;
        $this->id = $id ?: new UuidV4();
    }

    public function update(
        // @var Collection<int, App\Core\Domain\Model\RoleInUnit> $rolesInUnits
        Collection $rolesInUnit,
        ?Profile $profile = null,
    ): void {
        $this->rolesInUnits = $rolesInUnit;
        $this->profile = $profile;
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

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): static
    {
        $this->profile = $profile;

        return $this;
    }
}
