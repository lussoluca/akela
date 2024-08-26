<?php

declare(strict_types=1);

namespace App\Entity\Enum;

enum UnitType: string
{
    case TYPE_COCA = 'coca';
    case TYPE_BRANCO = 'branco';
    case TYPE_REPARTO = 'reparto';
    case TYPE_CLAN = 'clan';

    public function label(): string
    {
        return match ($this) {
            self::TYPE_COCA => 'Coca',
            self::TYPE_BRANCO => 'Branco',
            self::TYPE_REPARTO => 'Reparto',
            self::TYPE_CLAN => 'Clan',
        };
    }
}
