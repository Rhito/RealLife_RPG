<?php

namespace App\Listeners;

use App\Events\AdminLogEvent;
use App\Repositories\Contracts\AdminLogRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminLogListener //implements ShouldQueue
{
    protected $adminLog;
    /**
     * Create the event listener.
     */
    public function __construct(AdminLogRepositoryInterface $adminLog)
    {
        $this->adminLog = $adminLog;
    }

    /**
     * Handle the event.
     */
    public function handle(AdminLogEvent $event): void
    {
        try {
            $this->adminLog->log(
                $event->adminId,
                $event->action,
                $event->target->id,
                $event->target ? get_class($event->target) : null,
                $event->meta
            );
        } catch (\Throwable $e) {
            \dd('AdminLogListener failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
