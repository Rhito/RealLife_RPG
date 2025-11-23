<?php

namespace App\Enums;

enum TaskStatus: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case MISSED = 'skipped';
    case SKIPPED = 'skipped';

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'gray',
            self::COMPLETED => 'green',
            self::MISSED => 'red',
            self::SKIPPED => 'yellow',
        };
    }
}
