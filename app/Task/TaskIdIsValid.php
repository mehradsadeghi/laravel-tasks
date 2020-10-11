<?php

namespace App\Task;

use App\InvalidTaskIdBehavior;
use App\Policies\TaskPolicy;

class TaskIdIsValid
{
    public function handle($request, $next)
    {
        if (TaskPolicy::taskExists()) {
            return $next($request);
        }

        InvalidTaskIdBehavior::reaction();
        event('invalidTaskIdAttempted');

        return InvalidTaskIdBehavior::response();
    }
}