<?php

namespace App\Enums;

enum EventStatus: string
{
    case Active = 'active';
    case Paused = 'paused';
    case Closed = 'closed';
}
