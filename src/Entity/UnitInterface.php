<?php

declare(strict_types=1);

namespace App\Entity;

interface UnitInterface
{
    /**
     * Return the list of members that matches the role and that's approved.
     *
     * @param null|string $role
     *                              The role to check, null to include all roles
     * @param bool        $approved
     *                              True to extract only approved members
     *
     * @return array<int,UserInterface>
     */
    public function getMembers(?string $role = null, bool $approved = true): array;
}
