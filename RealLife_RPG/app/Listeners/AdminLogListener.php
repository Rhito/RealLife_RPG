<?php

namespace App\Listeners;

use App\Events\AdminLogEvent;
use App\Repositories\Contracts\AdminLogRepositoryInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class AdminLogListener implements ShouldQueue
{
    protected AdminLogRepositoryInterface $adminLog;
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
            $targetId = $event->data['id'] ?? null;

            // metadata
            $payload = array_merge($event->meta, [
                'snapshot_data' => $event->data
            ]);

            // call the log method of repository
            $this->adminLog->log(
                $event->adminId,
                $event->action,
                $targetId,          // Id take from array
                $event->targetType, // Target class take from property string
                $payload            // meta data include snapshot
            );
        } catch (\Throwable $e) {
            Log::error('[AminLogListener Error]: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
