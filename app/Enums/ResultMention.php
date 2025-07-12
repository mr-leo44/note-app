<?php

namespace App\Enums;

enum ResultMention: string
{
    case AJOURNE = 'ajourné';
    case SATISFACTION = 'satisfaction';
    case DISTINCTION = 'distinction';
    case GRANDE_DISTINCTION = 'grande distinction';
    case TRES_GRANDE_DISTINCTION = 'très grande distinction';

    public function label(): string
    {
        return match($this) {
            self::AJOURNE => 'Ajourné',
            self::SATISFACTION => 'Satisfaction',
            self::DISTINCTION => 'Distinction',
            self::GRANDE_DISTINCTION => 'Grande distinction',
            self::TRES_GRANDE_DISTINCTION => 'Très grande distinction',
        };
    }
}
