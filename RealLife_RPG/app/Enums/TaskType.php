<?php

namespace App\Enums;

enum TaskType: string
{
    case DAILY = 'daily';
    case ONCE = 'once';
    case HABIT = 'habit';
    case WEEKLY = 'weekly';
    case MONTHLY = 'monthly';
    case TODO = 'todo';
}
