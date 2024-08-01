<?php

declare(strict_types=1);

namespace App\Entity;


use Symfony\Component\Uid\UuidV4;

/**
 * Interface MembershipInterface.
 */
interface MembershipInterface
{
    public const MEMBERSHIP_STATE_REQUESTED = 'requested';
    public const MEMBERSHIP_STATE_APPROVED = 'approved';
    public const MEMBERSHIP_STATE_DENIED = 'denied';
    public const MEMBERSHIP_STATE_INACTIVE = 'inactive';

    public const MEMBERSHIP_TRANSITION_APPROVE = 'approve';
    public const MEMBERSHIP_TRANSITION_DENY = 'deny';
    public const MEMBERSHIP_TRANSITION_INACTIVATE = 'inactivate';

    public function getId(): UuidV4;

    public function getUser(): UserInterface;

    public function getOrganization(): OrganizationInterface;

    public function getUnit(): UnitInterface;

    public function getStatus(): string;

    public function getRole(): Role;

    public function setRole(Role $role): self;

    public function setStatus(string $status): self;

    public function isApproved(): bool;

    public function isDenied(): bool;

    public function isRequested(): bool;

    public function isInactive(): bool;

    public function isAdmin(): bool;

    public function isEditor(): bool;

    public function isMember(): bool;

    /**
     * @return array<int, string>
     */
    public function getAvailableActions(UserInterface $user): array;
}
