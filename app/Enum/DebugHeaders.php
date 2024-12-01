<?php

namespace App\Enum;

enum DebugHeaders: string
{
    case TIME = 'X-Debug-Time';
    case MEMORY = 'X-Debug-Memory';
}
