<?php

declare(strict_types=1);

namespace App\Entity\Core;

use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class NullablePhoneNumber extends PhoneNumber
{
    /**
     * PhoneNumber constructor.
     */
    public function __construct(?string $country, ?string $number)
    {
        parent::__construct($country ?? '', $number ?? '');

        $this->verificationCode = $this->generatePhoneCode();
    }
}
