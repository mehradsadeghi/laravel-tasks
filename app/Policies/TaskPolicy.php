<?php

namespace App\Policies;

use App\Task;

class TaskPolicy
{
    public static function taskExists()
    {
        $id = (int) request()->route()->parameter('task');

        return is_numeric($id) && (bool) Task::query()->find($id);
    }
}
