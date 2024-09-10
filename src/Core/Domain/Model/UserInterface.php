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

    public function getInitials(): string;

    public function isFirstLogin(): bool;

    /**
     * @phpstan-ignore-next-line
     */
    public function isDeleted();

    public function updatePassword(string $password): self;
}
