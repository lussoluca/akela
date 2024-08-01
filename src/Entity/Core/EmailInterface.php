<?php

declare(strict_types=1);

namespace App\Entity\Core;

/**
 * Interface EmailInterface.
 */
interface EmailInterface
{
    public function getAddress(): string;

    public function isVerified(): bool;

    /**
     * Create a new version of this email that is verified.
     *
     * @return $this
     */
    public function verified(): self;

    /**
     * Return TRUE if this and other email are equal.
     *
     * @param EmailInterface $other The other email
     *
     * @return bool TRUE if this and other email are equal
     */
    public function equal(EmailInterface $other): bool;
}
