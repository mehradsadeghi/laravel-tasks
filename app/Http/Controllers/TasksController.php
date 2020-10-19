<?php

namespace App\Http\Controllers;

use App\TaskManagement\DB\Task;
use App\TaskManagement\DB\TaskRepo;
use Illuminate\Routing\Controller;

class TasksController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function index()
    {
        $userId = auth()->id();

        return view('tasks.index', [
            'tasks' => TaskRepo::find($userId)->get(),
            'tasksComplete' => TaskRepo::find($userId, 'done')->get(),
            'tasksInComplete' => TaskRepo::withDefaultState($userId)->get(),
            'tasksDoing' => TaskRepo::find($userId, 'doing')->get(),
            'tasksFailed' => TaskRepo::find($userId, 'failed')->get(),
            'tasksWont_do' => TaskRepo::find($userId, 'skipped')->get(),
        ]);
    }

    public function store()
    {
        // validation: routes/validators.php
        $data = request()->only(['name', 'description']);

        $task = TaskRepo::saveNew($data, auth()->id());

        return redirect('/tasks')->with('success', 'Task '.$task->name.' created');
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

        return redirect('tasks')->with('success', 'Task State Updated');
    }

    public function destroy($id)
    {
        TaskRepo::remove($id);

        return redirect('/tasks')->with('success', 'Task Deleted');
    }
}
