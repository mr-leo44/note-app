<?php

namespace App\Enums;

enum ResultByPromotionStatus: string
{
    case COMPLETE = 'complete';
    case IN_PROGRESS = 'in_progress';
    case PUBLISHED = 'published';

    /**
     * Return the label of the enum value.
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::COMPLETE => 'Complète',
            self::IN_PROGRESS => 'En cours',
            self::PUBLISHED => 'Publiée',
        };
    }
}