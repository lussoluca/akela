<?php

declare(strict_types=1);

namespace App\Entity\Core;

/**
 * Interface PhoneNumberInterface.
 */
interface PhoneNumberInterface
{
    public function getNumber(): string;

    public function getCountry(): string;

    public function getVerificationCode(): string;

    /**
     * Create a new version of this phone number that is verified.
     *
     * @return $this
     */
    public function verified(): self;

    public function isVerified(): bool;

    public function getCountryPrefix(): int;

    /**
     * Return this number in E.164 format.
     *
     * @return string This number in E.164 format.
     */
    public function getFullNumber(): string;

    /**
     * Return TRUE if this and other phone numbers are equal.
     *
     * @param \App\Entity\Core\PhoneNumber $other The other phone number
     *
     * @return bool TRUE if this and other phone numbers are equal
     */
    public function equal(PhoneNumber $other): bool;
}
