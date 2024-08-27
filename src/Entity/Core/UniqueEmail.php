<?php

declare(strict_types=1);

namespace App\Entity\Core;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class UniqueEmail extends Email
{
    #[Column(type: 'string', length: 180, unique: true)]
    protected string $address;
}
