<?php

namespace App\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdminLogEvent
{
    use Dispatchable, SerializesModels;

    /**
     * Declare variable
     */
    public int $adminId; // Updated by
    public array $data; // Data snpashot (converted to array)
    public string $action; // Action(update, create, delete, restore,...)
    public array $meta; // Metadata (IP, UserAgent, etc.)
    public string $targetType; // Model name (EX: App\Models\Task)

    /**
     * Create a new event instance.
     *
     * @param int $adminId
     * @param array $dataObject (Model or Array)
     * @param string $action
     * @param array $meta
     */
    public function __construct(int $adminId, $dataObject, string $action, array $meta = [])
    {
        $this->adminId = $adminId;
        $this->action = $action;
        $this->meta = $meta;

        // convert object to array safe (Snapshot data)
        if ($dataObject instanceof Model) {
            $this->data = $dataObject->toArray();
        } elseif (is_array($dataObject)) {
            $this->data = $dataObject;
        } else {
            // in case data null or bool (fallback)
            $this->data = ['original_data' => $dataObject];
        }
        // remove token and password if exists
        $this->sanitizeData();
    }

    protected function sanitizeData(): void
    {
        $sensitiveFields = ['password', 'remember_token', 'token', 'access_token'];

        foreach ($sensitiveFields as $field) {
            if (isset($this->data[$field])) {
                // $this->data[$field] = '[HIDDEN]';
                unset($this->data[$field]);
            }
        }
    }
}
