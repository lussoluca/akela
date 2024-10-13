<?php

declare(strict_types=1);

namespace App\Core\Domain\Model;

use Symfony\Component\Uid\UuidV4;

/**
 * Interface GroupInterface.
 */
interface GroupInterface
{
    public function getId(): UuidV4;

    public function getName(): string;

    public function getCodiceOrdinale(): string;

    public function getIban(): string;

    public function getAddress(): Address;

    public function getUnits();

    public function update(
        string $name,
        string $codiceOrdinale,
        string $iban,
        Address $address,
    ): self;

    /**
     * Return TRUE if this and other group are equal.
     *
     * @param GroupInterface $other The other group
     *
     * @return bool TRUE if this and other group are equal
     */
    public function equal(GroupInterface $other): bool;
}
