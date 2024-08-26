<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

class Person
{
    #[OneToOne(inversedBy: 'person', cascade: ['persist', 'remove'])]
    private ?MedicalData $medicalData = null;

    /**
     * @var Collection<int, User>
     */
    #[ManyToMany(targetEntity: User::class, mappedBy: 'managedPersons')]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getMedicalData(): ?MedicalData
    {
        return $this->medicalData;
    }

    public function setMedicalData(?MedicalData $medicalData): static
    {
        $this->medicalData = $medicalData;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addManagedPerson($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeManagedPerson($this);
        }

        return $this;
    }
}
