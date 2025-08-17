<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdminLogEvent
{
    use Dispatchable, SerializesModels;

    /**
     * Declare variable
     */
    public $adminId; // Updated by
    public $target; // Data Updated
    public $action; // Action(update, create, delete, restore,...)
    public $meta; // Additional values

    /**
     * Create a new event instance.
     */
    public function __construct(int $adminId, $target, string $action, array $meta = [])
    {
        $this->adminId = $adminId;
        $this->target = $target;
        $this->action = $action;
        $this->meta = $meta;
    }
}
