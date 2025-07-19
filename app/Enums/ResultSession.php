<?php

namespace App\Enums;

enum ResultSession: string
{
    case S1 = 'Première Session';
    case S2 = 'Deuxième Session';

    public function label(): string
    {
        return match($this) {
            self::S1 => 'Première Session',
            self::S2 => 'Deuxième Session',
        };
    }
}
