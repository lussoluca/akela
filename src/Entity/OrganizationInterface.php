<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Core\AddressInterface;
use App\Entity\Core\EmailInterface;
use App\Entity\Core\PhoneNumber;
use App\Entity\Core\VisibleInterface;
use Symfony\Component\Uid\UuidV4;

/**
 * Interface OrganizationInterface.
 */
interface OrganizationInterface extends VisibleInterface
{
    public function getId(): UuidV4;

    public function getName(): string;

    public function getBusinessName(): string;

    public function getDescription(): string;

    public function getPhoneNumber(): ?PhoneNumber;

    public function getEmail(): ?EmailInterface;

    public function getWebsite(): ?string;

    public function getAddress(): AddressInterface;

    public function getSlug(): string;

    public function update(
        string $name,
        string $businessName,
        string $description,
        ?PhoneNumber $phoneNumber,
        ?EmailInterface $email,
        ?string $website,
        AddressInterface $address,
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
