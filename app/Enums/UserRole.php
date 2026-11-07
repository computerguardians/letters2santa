<?php

namespace App\Enums;

enum UserRole: string
{
    case Parent = 'parent';
    case Business = 'business';
    case Admin = 'admin';
}
