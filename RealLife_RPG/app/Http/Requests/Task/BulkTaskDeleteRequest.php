<?php

namespace App\Http\Requests\Task;

use App\http\Requests\BulkDeleteRequest;

class BulkTaskDeleteRequest extends BulkDeleteRequest
{
    protected function table(): string
    {
        return 'tasks';
    }
}
