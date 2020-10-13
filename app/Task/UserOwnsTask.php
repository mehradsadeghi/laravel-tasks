<?php

namespace App\Task;

use App\Behaviors\PreventTamperingOtherUsersTasks;

class UserOwnsTask
{
    public function handle($request, $next)
    {
        if (PreventTamperingOtherUsersTasks::ownsTask()) {
            return $next($request);
        }

        PreventTamperingOtherUsersTasks::reaction();
        event('tamperWithOthersTasks');

        return PreventTamperingOtherUsersTasks::response();
    }
}