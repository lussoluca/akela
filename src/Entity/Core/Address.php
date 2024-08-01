<?php

declare(strict_types=1);

namespace App\Entity\Core;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

/**
 * Class Address.
 */
#[Embeddable]
class Address implements \Stringable
{
    #[Column(name: 'city', type: 'string', length: 256, nullable: true)]
    protected $city;

    #[Column(name: 'province', type: 'string', length: 256, nullable: true)]
    protected $province;

    #[Column(name: 'postal_code', type: 'string', length: 5, nullable: true)]
    protected $postalCode;

    #[Column(name: 'address_line1', type: 'string', length: 256, nullable: true)]
    protected $addressLine1;

    #[Column(name: 'address_line2', type: 'string', length: 256, nullable: true)]
    protected $addressLine2;

    public function __construct(
        $city,
        $province = '',
        $postalCode = '',
        $addressLine1 = '',
        $addressLine2 = '',
    )
    {
        $this->city = $city;
        $this->province = $province;
        $this->postalCode = $postalCode;
        $this->addressLine1 = $addressLine1;
        $this->addressLine2 = $addressLine2;
    }

    public function __toString(): string
    {
        return sprintf('%s, %s %s (%s)', $this->addressLine1, $this->postalCode, $this->city, $this->province);
    }

    public function isEmpty(): bool
    {
        return empty($this->addressLine1) || empty($this->city) || empty($this->province);
    }
}
