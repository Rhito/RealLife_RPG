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
    public ?int $adminId; // Updated by
    public array $data; // Data snpashot (converted to array)
    public string $action; // Action(update, create, delete, restore,...)
    public array $meta; // Metadata (IP, UserAgent, etc.)
    public string $targetType; // Model name (EX: App\Models\Task)

    /**
     * Create a new event instance.
     *
     * @param int|null $adminId
     * @param mixed $rawTarget (Model or Array)
     * @param string $action
     * @param string $targetModel
     * @param array $meta
     */
    public function __construct($adminId, $rawTarget, string $action, array $meta = [])
    {
        $this->adminId = $adminId ?? auth('admin')->id() ?? null;

        $this->action = $action;

        $this->targetType = is_object($rawTarget) ? get_class($rawTarget) : 'Data/Array';

        $this->data = $this->prepareData($rawTarget);

        $email = $this->extractEmail($rawTarget);
        $this->meta = array_merge($meta, ['email' => $email]);
    }

    /**
     * Logic covert to array and sanitize Data
     */
    protected function prepareData($target): array
    {
        //covert to array
        $data = ($target instanceof Model) ? $target->toArray() : (array) $target;
        // sanitize
        $sensitiveFields = ['password', 'token', 'access_token', 'remember_token'];
        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                unset($data[$field]);
            }
        }
        return $data;
    }

    /**
     * Logic to extract email
     */
    protected function extractEmail($target): string
    {
        if ($target instanceof Model) {
            return $target->email ?? ($target->user->email ?? 'unknown');
        }
        return is_array($target) && isset($target['email']) ? $target['email'] : 'unknown';
    }
}
