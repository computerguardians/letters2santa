<?php

namespace App\Exceptions;

use App\Models\Event;
use RuntimeException;

class NoCreditsException extends RuntimeException
{
    public function __construct(public Event $event)
    {
        parent::__construct('This event is not accepting letters.');
    }
}
