<?php

declare(strict_types=1);

namespace App\Core\Domain\Model;

use App\Core\Domain\Model\Traits\CollectionsTrait;
use App\Core\Domain\Model\Traits\SoftDeleteableEntity;
use App\Core\Domain\Model\Traits\TimestampableEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToMany;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Uid\UuidV4;

#[Entity]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt')]
class User implements UserInterface
{
    use TimestampableEntity;
    use SoftDeleteableEntity;
    use CollectionsTrait;

    #[Id]
    #[Column(type: 'uuid')]
    private UuidV4 $id;

    #[Embedded(class: 'App\Core\Domain\Model\UniqueEmail', columnPrefix: 'email_')]
    private EmailInterface $email;

    #[Column(type: 'boolean')]
    private bool $isFirstLogin;

    /**
     * @var string The hashed password
     */
    #[Column(type: 'string')]
    private string $password;

    /**
     * @var Collection<int, Person>
     */
    #[ManyToMany(targetEntity: Person::class)]
    private Collection $managedPersons;

    /**
     * User constructor.
     */
    public function __construct(
        EmailInterface $email,
        string $password,
    ) {
        $this->id = new UuidV4();
        $this->isFirstLogin = true;
        $this->managedPersons = new ArrayCollection();
        $this->email = $email;
        $this->password = $password;
    }

    public function getId(): UuidV4
    {
        return $this->id;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getEmail(): EmailInterface
    {
        return $this->email;
    }

    public function getUserIdentifier(): string
    {
        return $this->getEmail()->getAddress();
    }

    public function getInitials(): string
    {
        return substr($this->getUserIdentifier(), 0, 1);
    }

    public function isFirstLogin(): bool
    {
        return $this->isFirstLogin;
    }

    public function updatePassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Person>
     */
    public function getManagedPersons(): Collection
    {
        return $this->managedPersons;
    }

    public function addManagedPerson(Person $managedPerson): static
    {
        if (!$this->managedPersons->contains($managedPerson)) {
            $this->managedPersons->add($managedPerson);
        }

        return $this;
    }

    public function removeManagedPerson(Person $managedPerson): static
    {
        $this->managedPersons->removeElement($managedPerson);

        return $this;
    }

    public function getRoles(): array
    {
        // TODO: Implement getRoles() method.
        return [];
    }
}
