<?php

declare(strict_types=1);

namespace App\Core\Domain\Model\Enum;

enum Role: string
{
    case ROLE_CAPO_GRUPPO = 'capo_gruppo';
    case ROLE_ANIMATORE_DI_COCA = 'animatore_di_coca';
    case ROLE_ASSISTENTE_ECCLESIASTICO = 'assistente_ecclesiastico';
    case ROLE_CAPO_BRANCO = 'capo_branco';
    case ROLE_AIUTO_CAPO_BRANCO = 'aiuto_capo_branco';
    case ROLE_CAPO_REPARTO = 'capo_reparto';
    case ROLE_AIUTO_CAPO_REPARTO = 'aiuto_capo_reparto';
    case ROLE_CAPO_FUOCO = 'capo_fuoco';
    case ROLE_AIUTO_CAPO_FUOCO = 'aiuto_capo_fuoco';
    case ROLE_CAPO_CLAN = 'capo_clan';
    case ROLE_AIUTO_CAPO_CLAN = 'aiuto_capo_clan';
    case ROLE_MAESTRO_DEI_NOVIZI = 'maestro_dei_novizi';

    public function label(): string
    {
        return match ($this) {
            self::ROLE_CAPO_GRUPPO => 'Capo Gruppo',
            self::ROLE_ANIMATORE_DI_COCA => 'Animatore di Coca',
            self::ROLE_ASSISTENTE_ECCLESIASTICO => 'Assistente Ecclesiastico',
            self::ROLE_CAPO_BRANCO => 'Capo Branco',
            self::ROLE_AIUTO_CAPO_BRANCO => 'Aiuto Capo Branco',
            self::ROLE_CAPO_REPARTO => 'Capo Reparto',
            self::ROLE_AIUTO_CAPO_REPARTO => 'Aiuto Capo Reparto',
            self::ROLE_CAPO_FUOCO => 'Capo Fuoco',
            self::ROLE_AIUTO_CAPO_FUOCO => 'Aiuto Capo Fuoco',
            self::ROLE_CAPO_CLAN => 'Capo Clan',
            self::ROLE_AIUTO_CAPO_CLAN => 'Aiuto Capo Clan',
            self::ROLE_MAESTRO_DEI_NOVIZI => 'Maestro dei Novizi',
        };
    }
}
