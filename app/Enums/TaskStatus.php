<?php

namespace App\Enums;

enum TaskStatus: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case MISSED = 'missed';
    case SKIPPED = 'skipped';
    case FAILED = 'failed';

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'gray',
            self::COMPLETED => 'green',
            self::MISSED => 'red',
            self::SKIPPED => 'yellow',
            self::FAILED => 'red',
        };
    }
}
