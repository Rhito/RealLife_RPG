<?php

namespace App\Http\Requests\Task;

use App\http\Requests\BulkDeleteRequest;

class BulkTaskRestoreRequest extends BulkDeleteRequest
{
    protected function table(): string
    {
        return 'tasks';
    }
}
