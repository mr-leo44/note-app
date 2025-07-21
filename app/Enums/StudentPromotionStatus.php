<?php

namespace App\Enums;

enum StudentPromotionStatus: string
{
    case EN_COURS = 'en cours';
    case DOUBLE = 'double';
    case REUSSIE = 'reussie';
    case RENVOYE = 'renvoyé';
    case DRAFT = 'brouillon';
    case COMPLETE = 'terminé';
    case PUBLISHED = 'publié'; 

    public function label(): string
    {
        return match($this) {
            self::EN_COURS => 'En cours',
            self::DOUBLE => 'Double',
            self::REUSSIE => 'Réussie',
            self::RENVOYE => 'Renvoyé',
            self::DRAFT => 'Brouillon',
            self::COMPLETE => 'Terminé',
            self::PUBLISHED => 'Publié',
        };
    }
}
