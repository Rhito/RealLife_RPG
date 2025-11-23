<?php

namespace App\Enums;

enum TaskType: string
{
    case GOOD_HABIT = 'good_habit';
    case BAD_HABIT = 'bad_habit';
    case DAILY = 'daily';
    case TODO = 'todo';
}
