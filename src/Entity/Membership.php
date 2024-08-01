<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Uid\UuidV4;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Gedmo\Mapping\Annotation as Gedmo;
use App\Entity\Core\Traits\TimestampableEntity;
use App\Entity\Core\Traits\SoftDeleteableEntity;

/**
 * Class Membership.
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
#[Entity]
class Membership implements MembershipInterface
{
    use TimestampableEntity;
    use SoftDeleteableEntity;

    #[Id]
    #[Column(type: 'uuid', unique: true)]
    private UuidV4 $id;

    #[ManyToOne(targetEntity: 'App\Entity\User', inversedBy: 'memberships')]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private UserInterface $user;

    #[Column(type: 'string', length: 255)]
    private string $status = MembershipInterface::MEMBERSHIP_STATE_REQUESTED;

    #[Embedded(class: 'App\Entity\Role')]
    private Role $role;

    #[ManyToOne(targetEntity: 'App\Entity\Unit', inversedBy: 'memberships')]
    private Unit $unit;

    /**
     * Membership constructor.
     */
    public function __construct(
        UserInterface $user,
        string        $status,
        Role          $role,
        UnitInterface $unit,
    )
    {
        $this->id = new \Symfony\Component\Uid\UuidV4();
        $this->user = $user;
        $this->status = $status;
        $this->unit = $unit;
        $this->role = $role;
    }

    public function getId(): \Symfony\Component\Uid\UuidV4
    {
        return $this->id;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function getOrganization(): OrganizationInterface
    {
        return $this->unit->getGroup();
    }

    public function getUnit(): UnitInterface
    {
        return $this->unit;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function setRole(Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function isApproved(): bool
    {
        return MembershipInterface::MEMBERSHIP_STATE_APPROVED == $this->getStatus();
    }

    public function isDenied(): bool
    {
        return MembershipInterface::MEMBERSHIP_STATE_DENIED == $this->getStatus();
    }

    public function isRequested(): bool
    {
        return MembershipInterface::MEMBERSHIP_STATE_REQUESTED == $this->getStatus();
    }

    public function isInactive(): bool
    {
        return MembershipInterface::MEMBERSHIP_STATE_INACTIVE == $this->getStatus();
    }

    public function isAdmin(): bool
    {
        return Role::ROLE_ORGANIZATION_ADMIN == $this->getRole()->getName();
    }

    public function isEditor(): bool
    {
        return Role::ROLE_ORGANIZATION_EDITOR == $this->getRole()->getName();
    }

    public function isMember(): bool
    {
        return Role::ROLE_ORGANIZATION_MEMBER == $this->getRole()->getName();
    }

    public function getAvailableActions(UserInterface $user): array
    {
        // Action for myself.
        if ($user->equal($this->getUser())) {
            return [];
        }

        if ($this->isApproved() && $this->isMember()) {
            return [
                'role_editor',
                'role_admin',
                'inactivate',
            ];
        }

        if ($this->isApproved() && $this->isEditor()) {
            return [
                'role_member',
                'role_admin',
                'inactivate',
            ];
        }

        if ($this->isApproved() && $this->isAdmin()) {
            return [
                'role_member',
                'role_editor',
                'inactivate',
            ];
        }

        if ($this->isInactive()) {
            return [];
        }

        if ($this->isRequested()) {
            return [
                'approve',
                'deny',
            ];
        }

        return [];
    }
}
