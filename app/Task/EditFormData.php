<?php

namespace App\Task;

class EditFormData
{
    public function handle($req)
    {
        $taskId = request()->route()->parameter('task');

        return ['task' => \App\Task::query()->find($taskId)];
    }
}