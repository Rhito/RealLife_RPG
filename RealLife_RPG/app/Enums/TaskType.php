<?php

namespace App\Enums;

enum TaskType : string
{
    case DAILY = 'daily';
    case ONCE = 'once';
    case HABIT = 'habit';
}
