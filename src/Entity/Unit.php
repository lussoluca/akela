<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Column;
use Symfony\Component\Uid\UuidV4;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use App\Entity\Core\Traits\CollectionsTrait;
use App\Entity\Core\Traits\TimestampableEntity;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Core\Traits\SoftDeleteableEntity;

#[Entity]
class Unit implements UnitInterface
{
    use TimestampableEntity;
    use SoftDeleteableEntity;
    use CollectionsTrait;

    #[Id]
    #[Column(type: 'uuid')]
    private UuidV4 $id;

    #[Column(type: 'string', length: 255)]
    private string $name;

    /**
     * @Gedmo\Slug(fields={"name"})
     */
    #[Column(length: 128)]
    private string $slug;

    #[ManyToOne(targetEntity: 'App\Entity\ScoutGroup', inversedBy: 'units')]
    #[JoinColumn(name: 'organization_id', referencedColumnName: 'id')]
    private OrganizationInterface $group;

    /**
     * @var Collection<int,Membership>
     */
    #[OneToMany(mappedBy: 'unit', targetEntity: 'App\Entity\Membership')]
    private Collection $memberships;

    public function __construct(
        string                $name,
        OrganizationInterface $group,
    )
    {
        $this->name = $name;
        $this->group = $group;

        $this->memberships = new ArrayCollection();
    }

    public function getMembers(?string $role = null, bool $approved = true): array
    {
        $members = [];
        foreach ($this->memberships as $membership) {
            if ($approved) {
                if ($membership->isApproved()) {
                    if ($role) {
                        if ($role == $membership->getRole()->getName()) {
                            $members[] = $membership->getUser();
                        }
                    } else {
                        $members[] = $membership->getUser();
                    }
                }
            } else {
                if ($role) {
                    if ($role == $membership->getRole()->getName()) {
                        $members[] = $membership->getUser();
                    }
                } else {
                    $members[] = $membership->getUser();
                }
            }
        }

        return $members;
    }

    public function getGroup(): OrganizationInterface
    {
        return $this->group;
    }

    public function getId(): UuidV4
    {
        return $this->id;
    }

    public function getMemberships(): Collection
    {
        return $this->memberships;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }
}
