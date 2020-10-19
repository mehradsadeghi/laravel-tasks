<?php

namespace App\TaskManagement\DB\Task;

class EditFormData
{
    public function handle($req)
    {
        $taskId = request()->route()->parameter('task');

        return ['task' => \App\TaskManagement\DB\Task::query()->find($taskId)];
    }
}