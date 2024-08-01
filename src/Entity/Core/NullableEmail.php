<?php

declare(strict_types=1);

namespace App\Entity\Core;

use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class NullableEmail extends Email
{
    /**
     * Email constructor.
     */
    public function __construct(?string $address)
    {
        parent::__construct($address ?? '');
    }
}
