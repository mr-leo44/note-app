<?php

namespace App\Enums;

enum StudentPromotionStatus: string
{
    case EN_COURS = 'en cours';
    case DOUBLE = 'double';
    case REUSSIE = 'reussie';
    case RENVOYE = 'renvoyé';

    public function label(): string
    {
        return match($this) {
            self::EN_COURS => 'En cours',
            self::DOUBLE => 'Double',
            self::REUSSIE => 'Réussie',
            self::RENVOYE => 'Renvoyé',
        };
    }
}
