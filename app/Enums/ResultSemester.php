<?php

namespace App\Enums;

enum ResultSemester: string
{
    case SEM1 = 'premier semestre';
    case SEM2 = 'deuxieme semestre';

    public function label(): string
    {
        return match($this) {
            self::SEM1 => 'Premier Semestre',
            self::SEM2 => 'Deuxième Semestre',
        };
    }
}
