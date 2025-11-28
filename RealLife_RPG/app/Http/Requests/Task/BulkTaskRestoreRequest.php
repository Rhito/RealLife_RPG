<?php

namespace App\Http\Requests\Task;

use App\Http\Requests\BulkRestoreRequest;

class BulkTaskRestoreRequest extends BulkRestoreRequest
{
    protected function table(): string
    {
        return 'tasks';
    }
}
