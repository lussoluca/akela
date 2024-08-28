<?php

declare(strict_types=1);

namespace App\Core\Domain\Model;

use Symfony\Component\Uid\UuidV4;

/**
 * Interface OrganizationInterface.
 */
interface GroupInterface
{
    public function getId(): UuidV4;

    public function getName(): string;

    public function getDescription(): string;

    public function update(
        string $name,
        string $description,
    ): self;

    /**
     * Return TRUE if this and other organization are equal.
     *
     * @param GroupInterface $other The other organization
     *
     * @return bool TRUE if this and other organization are equal
     */
    public function equal(GroupInterface $other): bool;
}
