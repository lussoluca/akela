<?php

declare(strict_types=1);

namespace App\Core\Domain\Model;

use Symfony\Component\Uid\UuidV4;

/**
 * Interface OrganizationInterface.
 */
interface OrganizationInterface extends VisibleInterface
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
     * @param OrganizationInterface $other The other organization
     *
     * @return bool TRUE if this and other organization are equal
     */
    public function equal(OrganizationInterface $other): bool;
}
