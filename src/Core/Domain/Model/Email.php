<?php

declare(strict_types=1);

namespace App\Core\Domain\Model;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use Symfony\Component\Mime\Address;

#[Embeddable]
class Email implements EmailInterface, \Stringable
{
    #[Column(type: 'string', length: 180, unique: false)]
    protected string $address;

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

    public function toAddress(): Address
    {
        return new Address($this->getAddress());
    }

    public function equal(EmailInterface $other): bool
    {
        return $this->getAddress() == $other->getAddress();
    }
}
