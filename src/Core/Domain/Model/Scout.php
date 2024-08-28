<?php

namespace App\Core\Domain\Model;

use App\Core\Infrastructure\Persistence\Repository\ScoutRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: ScoutRepository::class)]
class Scout extends Person
{
    #[ORM\ManyToOne]
    private ?Profile $parent1Profile = null;

    #[ORM\ManyToOne]
    private ?Profile $parent2Profile = null;

    #[ORM\ManyToOne]
    private ?Profile $ownProfile = null;

    public function __construct()
    {
        $this->id = new UuidV4();
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
