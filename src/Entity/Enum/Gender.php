<?php

declare(strict_types=1);

namespace App\Entity\Enum;

enum Gender: string
{
    case MALE = 'male';
    case FEMALE = 'female';

    case UNDEFINED = 'undefined';

    public function label(): string
    {
        return match ($this) {
            self::MALE => 'Maschio',
            self::FEMALE => 'Femmina',
            self::UNDEFINED => 'Non definito',
        };
    }
}
