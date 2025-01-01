<?php

declare(strict_types=1);

namespace App\Core\Domain\Model;

use Symfony\Component\Mime\Address;

/**
 * Interface EmailInterface.
 */
interface EmailInterface
{
    /**
     * @return non-empty-string
     */
    public function getAddress(): string;

    public function toAddress(): Address;

    /**
     * Return TRUE if this and other email are equal.
     *
     * @param EmailInterface $other The other email
     *
     * @return bool TRUE if this and other email are equal
     */
    public function equal(EmailInterface $other): bool;
}
