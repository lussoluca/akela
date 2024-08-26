<?php

declare(strict_types=1);

namespace App\Entity;

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
use Doctrine\ORM\Mapping\OneToMany;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Uid\UuidV4;

/**
 * Class Organization.
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
#[Entity]
class ScoutGroup implements OrganizationInterface
{
    use TimestampableEntity;
    use SoftDeleteableEntity;
    use CollectionsTrait;

    #[Id]
    #[Column(type: 'uuid', unique: true)]
    private UuidV4 $id;

    #[Column(type: 'string', length: 255)]
    private string $name;

    #[Column(type: 'string', length: 255)]
    private string $businessName;

    #[Column(type: 'text')]
    private string $description;

    #[Embedded(class: 'App\Entity\Core\PhoneNumber', columnPrefix: 'phone_')]
    private ?PhoneNumber $phoneNumber;

    #[Embedded(class: 'App\Entity\Core\NullableEmail', columnPrefix: 'email_')]
    private ?EmailInterface $email;

    #[Column(type: 'text', nullable: true)]
    private ?string $website;

    #[Embedded(class: 'App\Entity\Core\Address', columnPrefix: 'address_')]
    private Core\AddressInterface $address;

    /**
     * @Gedmo\Slug(fields={"name"})
     */
    #[Column(length: 128, unique: true)]
    private string $slug; // @phpstan-ignore-line

    /**
     * @var \Doctrine\Common\Collections\Collection<int,\App\Entity\Unit>
     */
    #[OneToMany(targetEntity: 'App\Entity\Unit', mappedBy: 'group')]
    private Collection $units;

    /**
     * Organization constructor.
     */
    public function __construct(
        string $name,
        string $businessName,
        string $description,
        ?PhoneNumber $phoneNumber,
        ?EmailInterface $email,
        ?string $website,
        Core\AddressInterface $address,
    ) {
        $this->id = new UuidV4();
        $this->name = $name;
        $this->businessName = $businessName;
        $this->description = $description;
        $this->phoneNumber = $phoneNumber;
        $this->email = $email;
        $this->website = $website;
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

    public function getBusinessName(): string
    {
        return $this->businessName;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPhoneNumber(): ?PhoneNumber
    {
        return $this->phoneNumber;
    }

    public function getEmail(): ?EmailInterface
    {
        return $this->email;
    }

    public function getWebsite(): ?string
    {
        $website = $this->website;

        if (null == $website) {
            return null;
        }

        if (!preg_match('~^(?:f|ht)tps?://~i', $website)) {
            $website = 'http://'.$website;
        }

        return $website;
    }

    public function getAddress(): Core\AddressInterface
    {
        return $this->address;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function update(
        string $name,
        string $businessName,
        string $description,
        ?PhoneNumber $phoneNumber,
        ?EmailInterface $email,
        ?string $website,
        Core\AddressInterface $address,
    ): self {
        $this->name = $name;
        $this->businessName = $businessName;
        $this->description = $description;
        $this->email = $email;
        $this->website = $website;
        $this->address = $address;

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
