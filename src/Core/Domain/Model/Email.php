<?php

declare(strict_types=1);

namespace App\Core\Domain\Model;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class Email implements EmailInterface, \Stringable
{
    #[Column(type: 'string', length: 180, unique: false)]
    protected string $address;

    #[Column(name: 'verified', type: 'boolean')]
    protected bool $verified = false;

    /**
     * Email constructor.
     */
    public function __construct(string $address)
    {
        $this->address = $address;
    }

    public function __toString(): string
    {
        return $this->getAddress();
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function isVerified(): bool
    {
        return $this->verified;
    }

    public function verified(): self
    {
        $new_email = clone $this;
        $new_email->verified = true;

        return $new_email;
    }

    public function equal(EmailInterface $other): bool
    {
        return $this->getAddress() == $other->getAddress();
    }
}
