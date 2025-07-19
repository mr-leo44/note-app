<?php

namespace App\Enums;

enum ResultMention: string
{
    case A = 'ajourné'; // < 50
    case S = 'satisfaction'; // >= 50 && < 70
    case D = 'distinction'; // >=70
    case GD = 'grande distinction'; // >= 80
    case TGD = 'très grande distinction'; // >= 90

    public function label(): string
    {
        return match($this) {
            self::A => 'Ajourné',
            self::S => 'Satisfaction',
            self::D => 'Distinction',
            self::GD => 'Grande distinction',
            self::TGD => 'Très grande distinction',
        };
    }
}
