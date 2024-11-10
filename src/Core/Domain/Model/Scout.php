<?php

namespace App\Core\Domain\Model;

use App\Core\Domain\Model\Traits\SoftDeleteableEntity;
use App\Core\Domain\Model\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity]
class Scout extends Person
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    #[Column(type: 'boolean')]
    protected bool $isAdult;

    #[ORM\ManyToOne]
    protected ?Profile $parent1Profile = null;

    #[ORM\ManyToOne]
    protected ?Profile $parent2Profile = null;

    #[ORM\ManyToOne]
    protected ?Profile $ownProfile = null;

    public function __construct()
    {
        $this->id = new UuidV4();
    }

    public function isAdult(): ?bool
    {
        return $this->isAdult;
    }

    public function setIsAdult(bool $isAdult): static
    {
        $this->isAdult = $isAdult;

        return $this;
    }

    public function getParent1Profile(): ?Profile
    {
        return $this->parent1Profile;
    }

    public function setParent1Profile(?Profile $parent1Profile): static
    {
        $this->parent1Profile = $parent1Profile;

        return $this;
    }

    public function getParent2Profile(): ?Profile
    {
        return $this->parent2Profile;
    }

    public function setParent2Profile(?Profile $parent2Profile): static
    {
        $this->parent2Profile = $parent2Profile;

        return $this;
    }

    public function getOwnProfile(): ?Profile
    {
        return $this->ownProfile;
    }

    public function setOwnProfile(?Profile $ownProfile): static
    {
        $this->ownProfile = $ownProfile;

        return $this;
    }
}
