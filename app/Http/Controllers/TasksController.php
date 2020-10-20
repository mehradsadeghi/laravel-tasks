<?php

namespace App\Http\Controllers;

use App\TaskManagement\DB\Task;
use App\TaskManagement\DB\TaskRepo;
use Illuminate\Routing\Controller;

class TasksController extends Controller
{
    public function store()
    {
        // validation? routes/validators.php
        $data = request()->only(['name', 'description']);
        $task = TaskRepo::saveNew($data, auth()->id());

        return $this->redirectIndex('Task '.$task->name.' created');
    }

    public function edit($id)
    {
        $task = Task::query()->find($id);
        $state = TaskRepo::getState($task);

        return compact('task', 'state');
    }

    public function update($id)
    {
        // validation: routes/validators.php
        TaskRepo::changeState($id, request('state'));

        return $this->redirectIndex('Task State Updated to '.request('state'));
    }

    public function destroy($id)
    {
        [$task, ] = TaskRepo::remove($id);

        return $this->redirectIndex("Task '{$task->name}' Deleted");
    }

    public function redirectIndex($msg)
    {
        return redirect()->route('tasks.index')->with(['success' => $msg]);
    }
}
