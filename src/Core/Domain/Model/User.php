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

/**
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
#[Entity]
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

    #[Column(type: 'string', length: 255)]
    private string $name;

    #[Column(type: 'string', length: 255)]
    private string $surname;

    /**
     * @var string The hashed password
     */
    #[Column(type: 'string')]
    private string $password;

    /**
     * @var Collection<int, Person>
     */
    #[ManyToMany(targetEntity: Person::class, inversedBy: 'users')]
    private Collection $managedPersons;

    /**
     * User constructor.
     */
    public function __construct(
        EmailInterface $email,
        string $password,
        string $name,
        string $surname
    ) {
        $this->id = new UuidV4();
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
        $this->surname = $surname;
        $this->managedPersons = new ArrayCollection();
    }

    public function getId(): UuidV4
    {
        return $this->id;
    }

    public function getEmail(): EmailInterface
    {
        return $this->email;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function isVerified(): bool
    {
        return $this->getEmail()->isVerified();
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return $this->getName().' '.$this->getSurname();
    }

    public function getUserIdentifier(): string
    {
        return $this->getUsername();
    }

    public function getInitials(): string
    {
        return
            substr($this->getName(), 0, 1)
            .
            substr($this->getSurname(), 0, 1);
    }

    public function verifyEmail(): self
    {
        $verified_email = $this->email->verified();
        $this->email = $verified_email;

        // Ensure that user is not marked as deleted when the mail is valid.
        $this->setDeletedAt(null);

        return $this;
    }

    /**
     * @return $this
     */
    public function update(
        EmailInterface $email,
        string $name,
        string $surname,
    ): self {
        if (!$this->email->equal($email)) {
            $this->email = $email;
        }

        $this->name = $name;
        $this->surname = $surname;

        return $this;
    }

    public function updatePassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function equal(UserInterface $other): bool
    {
        return $this->email->getAddress() == $other->getEmail()->getAddress();
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