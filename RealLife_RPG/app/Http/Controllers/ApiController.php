<?php

namespace App\Http\Controllers;

use App\Events\AdminLogEvent;
use App\Http\Controllers\Controller;
use App\Traits\HttpResposeTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    use HttpResposeTrait;

    // App\Http\Controllers\ApiController.php
    protected function handleException(\Throwable $e, string $customMessage = 'Server error'): JsonResponse
    {
        if ($e instanceof ModelNotFoundException) {
            return $this->error("Not found", ['error' => $e->getMessage()], 404);
        }

        if ($e instanceof AuthorizationException) {
            return $this->error("Unauthorized", ['error' => $e->getMessage()], 403);
        }

        if ($e instanceof QueryException) {
            return $this->error("Database error", ['error' => $e->getMessage()]);
        }

        return $this->error($customMessage, ['error' => $e->getMessage()]);
    }

    /**
     * Log action event store in db
     * @param mixed $action
     * @param mixed $target
     *
     * @return new AdminLogEvent
     */
    protected function logAction($action, $target)
    {
        /**
         * hide the password properties to secure
         */
        if (isset($target?->password)) {
            unset($target->password);
        }
        /**
         * call event to log action
         */
        return event(
            new AdminLogEvent(
                auth('admin')->id(),
                $target,
                $action,
                [
                    'email' => $target->email,
                    $target
                ]
            )
        );
    }
}
