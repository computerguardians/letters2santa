<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum EnquiryType: string implements HasLabel
{
    case Demo = 'demo';
    case Setup = 'setup';
    case Question = 'question';

    public function getLabel(): string
    {
        return match ($this) {
            self::Demo => 'Book a demo',
            self::Setup => 'Help me set it up',
            self::Question => 'General question',
        };
    }
}
