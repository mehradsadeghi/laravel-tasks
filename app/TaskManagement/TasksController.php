<?php

namespace App\TaskManagement;

use App\TaskManagement\DB\Task;
use App\TaskManagement\DB\TaskRepo;
use Illuminate\Routing\Controller;

class TasksController extends Controller
{
    public function store()
    {
        $data = request()->only(['name', 'description']);
        $task = TaskRepo::saveNew($data, auth()->id());

        return $this->msg('Task '.$task->name.' created');
    }

    public function edit($id)
    {
        $task = Task::query()->find($id);
        $state = TaskRepo::getState($task);

        return compact('task', 'state');
    }

    public function update($id)
    {
        TaskRepo::changeState($id, request('state'));

        return $this->msg('Task State Updated to '.request('state'));
    }

    public function destroy($id)
    {
        [$task, ] = TaskRepo::remove($id);

        return $this->msg("Task '{$task->name}' Deleted");
    }

    public function msg($msg)
    {
        return (['success' => $msg]);
    }
}
