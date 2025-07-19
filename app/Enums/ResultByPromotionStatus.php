<?php

namespace App\Enums;

enum ResultByPromotionStatus: string
{
    case COMPLETE = 'complete';
    case DRAFT = 'brouillon';
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
            self::DRAFT => 'Brouillon',
            self::PUBLISHED => 'Publiée',
        };
    }
}