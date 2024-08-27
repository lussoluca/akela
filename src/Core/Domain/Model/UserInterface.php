<?php

declare(strict_types=1);

namespace App\Core\Domain\Model;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;
use Symfony\Component\Uid\UuidV4;

/**
 * Interface UserInterface.
 */
interface UserInterface extends SymfonyUserInterface, PasswordAuthenticatedUserInterface
{
    public function getId(): UuidV4;

    public function getEmail(): EmailInterface;

    public function getPassword(): ?string;

    public function getName(): string;

    public function getSurname(): string;

    public function getInitials(): string;

    public function isVerified(): bool;

    public function verifyEmail(): self;

    /**
     * @phpstan-ignore-next-line
     */
    public function isDeleted();

    public function update(
        EmailInterface $email,
        string $name,
        string $surname,
    ): self;

    public function updatePassword(string $password): self;

    /**
     * Return TRUE if this and other user are equal.
     *
     * @param UserInterface $other The other user
     *
     * @return bool TRUE if this and other user are equal
     */
    public function equal(UserInterface $other): bool;
}
