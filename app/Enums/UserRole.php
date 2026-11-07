<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum UserRole: string implements HasLabel, HasColor
{
    case Parent = 'parent';
    case Business = 'business';
    case Admin = 'admin';

    public function getLabel(): string
    {
        return ucfirst($this->value);
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Parent => 'info',
            self::Business => 'primary',
            self::Admin => 'danger',
        };
    }
}
