<?php

declare(strict_types=1);

namespace App\Core\Domain\Model;

use CommerceGuys\Addressing\Address as BaseAddress;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use JetBrains\PhpStorm\Pure;

/**
 * Class Address.
 */
#[Embeddable]
class Address extends BaseAddress implements \Stringable
{
    #[Column(name: 'country_code', type: 'string', length: 3, nullable: false)]
    protected string $countryCode;

    #[Column(name: 'administrative_area', type: 'string', length: 32, nullable: false)]
    protected string $administrativeArea;

    #[Column(name: 'locality', type: 'string', length: 256, nullable: true)]
    protected string $locality;

    #[Column(name: 'dependent_locality', type: 'string', length: 256, nullable: true)]
    protected string $dependentLocality;

    #[Column(name: 'postal_code', type: 'string', length: 5, nullable: true)]
    protected string $postalCode;

    #[Column(name: 'address_line1', type: 'string', length: 256, nullable: true)]
    protected string $addressLine1;

    #[Column(name: 'address_line2', type: 'string', length: 256, nullable: true)]
    protected string $addressLine2;

    #[Column(name: 'locale', type: 'string', length: 2, nullable: true)]
    protected string $locale;

    #[Pure]
    public function __construct(
        $countryCode,
        $administrativeArea,
        $locality,
        $dependentLocality = '',
        $postalCode = '',
        $sortingCode = '',
        $addressLine1 = '',
        $addressLine2 = '',
        $organization = '',
        $givenName = '',
        $additionalName = '',
        $familyName = '',
        $locale = 'it'
    ) {
        parent::__construct(
            $countryCode,
            $administrativeArea,
            $locality,
            $dependentLocality,
            $postalCode,
            $sortingCode,
            $addressLine1,
            $addressLine2,
            '',
            $organization,
            $givenName,
            $additionalName,
            $familyName,
            $locale
        );
    }

    public function __toString(): string
    {
        return sprintf('%s, %s %s (%s)', $this->addressLine1, $this->postalCode, $this->locality, $this->administrativeArea);
    }

    public function isEmpty(): bool
    {
        return empty($this->countryCode) || empty($this->administrativeArea) || empty($this->locality);
    }
}
