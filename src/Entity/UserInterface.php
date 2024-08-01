<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;

/**
 * Interface UserInterface.
 */
interface UserInterface extends SymfonyUserInterface, PasswordAuthenticatedUserInterface
{
    public function getId(): UuidV4;

    public function getEmail(): Core\EmailInterface;

    public function getPassword(): ?string;

    public function getPhoneNumber(): Core\PhoneNumber;

    public function getAddress(): Core\AddressInterface;

    public function getBirthYear(): int;

    public function getName(): string;

    public function getSurname(): string;

    public function getBio(): ?string;

    public function getInitials(): string;

    /**
     * @return array<string, \App\Entity\OrganizationInterface>
     */
    public function getOrganizations(): array;

    public function isVisible(): bool;

    public function getSettings(): SettingsInterface;

    public function isVerified(): bool;

    public function verifyPhoneNumber(): self;

    public function verifyEmail(): self;

    /**
     * @phpstan-ignore-next-line
     */
    public function isDeleted();

    public function update(
        Core\EmailInterface   $email,
        Core\PhoneNumber      $phoneNumber,
        Core\AddressInterface $address,
        int                   $birthYear,
        string                $name,
        string                $surname,
        ?string               $bio,
    ): self;

    public function updatePassword(string $password): self;

    public function updateSettings(SettingsInterface $settings): self;

    /**
     * Return TRUE if this and other user are equal.
     *
     * @param UserInterface $other The other user
     *
     * @return bool TRUE if this and other user are equal
     */
    public function equal(UserInterface $other): bool;
}
