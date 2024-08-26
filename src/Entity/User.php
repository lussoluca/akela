<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Core\AddressInterface;
use App\Entity\Core\EmailInterface;
use App\Entity\Core\PhoneNumber;
use App\Entity\Core\Traits\CollectionsTrait;
use App\Entity\Core\Traits\SoftDeleteableEntity;
use App\Entity\Core\Traits\TimestampableEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
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

    #[Embedded(class: 'App\Entity\Core\UniqueEmail', columnPrefix: 'email_')]
    private EmailInterface $email;

    #[Embedded(class: 'App\Entity\Core\PhoneNumber', columnPrefix: 'phone_')]
    private PhoneNumber $phoneNumber;

    #[Embedded(class: 'App\Entity\Core\Address', columnPrefix: 'address_')]
    private AddressInterface $address;

    #[Column(type: 'integer', length: 4)]
    private int $birthYear;

    #[Column(type: 'string', length: 255)]
    private string $name;

    #[Column(type: 'string', length: 255)]
    private string $surname;

    #[ManyToOne(targetEntity: 'App\Entity\Unit', inversedBy: 'users')]
    #[JoinColumn(name: 'unit_id', referencedColumnName: 'id')]
    private Unit $unit;

    /**
     * @var string[]
     */
    #[Column(type: 'json')]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[Column(type: 'string')]
    private string $password;

    #[Column(type: 'text', nullable: true)]
    private ?string $bio;

    #[OneToOne(targetEntity: 'App\Entity\Settings', cascade: ['persist'])]
    #[JoinColumn(name: 'settings_id', referencedColumnName: 'id', nullable: true)]
    private SettingsInterface $settings;

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
        PhoneNumber $phoneNumber,
        AddressInterface $address,
        int $birthYear,
        string $name,
        string $surname
    ) {
        $this->id = new UuidV4();
        $this->email = $email;
        $this->password = $password;
        $this->phoneNumber = $phoneNumber;
        $this->address = $address;
        $this->birthYear = $birthYear;
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

    public function getPhoneNumber(): PhoneNumber
    {
        return $this->phoneNumber;
    }

    public function getAddress(): AddressInterface
    {
        return $this->address;
    }

    public function getBirthYear(): int
    {
        return $this->birthYear;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function isVerified(): bool
    {
        return
            $this->getEmail()->isVerified()
            && $this->getPhoneNumber()->isVerified();
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

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function isVisible(): bool
    {
        return $this->getSettings()->hasPublicProfile();
    }

    public function getSettings(): SettingsInterface
    {
        return $this->settings ?? new DefaultSettings();
    }

    public function verifyPhoneNumber(): self
    {
        $verified_phone_number = $this->phoneNumber->verified();
        $this->phoneNumber = $verified_phone_number;

        return $this;
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
        PhoneNumber $phoneNumber,
        AddressInterface $address,
        int $birthYear,
        string $name,
        string $surname,
        ?string $bio,
    ): self {
        if (!$this->phoneNumber->equal($phoneNumber)) {
            $this->phoneNumber = $phoneNumber;
        }

        if (!$this->email->equal($email)) {
            $this->email = $email;
        }

        $this->address = $address;
        $this->birthYear = $birthYear;
        $this->name = $name;
        $this->surname = $surname;
        $this->bio = $bio;

        return $this;
    }

    public function updatePassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function updateSettings(SettingsInterface $settings): self
    {
        $this->settings = $settings;

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
}
