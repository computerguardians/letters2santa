<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum LetterSource: string implements HasLabel, HasColor
{
    case Individual = 'individual';
    case Party = 'party';

    public function getLabel(): string
    {
        return ucfirst($this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Individual => 'info',
            self::Party => 'primary',
        };
    }
}
