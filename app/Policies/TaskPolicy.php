<?php

namespace App\Policies;

use App\Task;

class TaskPolicy
{
    public static function ownsTask()
    {
        $id = (int) request()->route()->parameter('task');

        return auth()->id() == Task::query()->find($id)->user_id;
    }

    public static function affordableNumber()
    {
        return Task::getCount(auth()->id()) < 10;
    }

    public static function taskExists()
    {
        $id = (int) request()->route()->parameter('task');

        return is_numeric($id) && (bool) Task::query()->find($id);
    }
}
