<?php

namespace App\Task;

use App\Policies\TaskPolicy;
use App\TamperWithOthersTasks;

class UserOwnsTask
{
    public function handle($request, $next)
    {
        if (TaskPolicy::ownsTask()) {
            return $next($request);
        }

        TamperWithOthersTasks::reaction();
        event('tamperWithOthersTasks');

        return TamperWithOthersTasks::response();
    }
}