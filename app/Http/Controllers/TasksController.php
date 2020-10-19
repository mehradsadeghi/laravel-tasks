<?php

namespace App\Http\Controllers;

use App\TaskManagement\DB\Task;
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
            'tasks' => Task::ofUserId($userId)->get(),
            'tasksComplete' => Task::ofUserId($userId)->whereStateIs('done')->get(),
            'tasksInComplete' => Task::ofUserId($userId)->withDefaultState()->get(),
            'tasksDoing' => Task::ofUserId($userId)->whereStateIs('doing')->get(),
            'tasksFailed' => Task::ofUserId($userId)->whereStateIs('failed')->get(),
            'tasksWont_do' => Task::ofUserId($userId)->whereStateIs('wont_do')->get(),
        ]);
    }

    public function store()
    {
        // validation: routes/validators.php
        $data = request()->only(['name', 'description']);
        $data['user_id'] = auth()->id();

        $task = Task::query()->create($data);

        // By default we do not tag the tasks.
        // so if there is not tag it means they are not tried

        return redirect('/tasks')->with('success', 'Task '.$task->name.' created');
    }

    public function edit($id)
    {
        $task = Task::query()->find($id);

        return compact('task');
    }

    public function update($id)
    {
        // validation: routes/validators.php
        Task::query()->find($id)->setState(request('state'));

        return redirect('tasks')->with('success', 'Task State Updated');
    }

    public function destroy($id)
    {
        Task::remove($id);

        return redirect('/tasks')->with('success', 'Task Deleted');
    }
}
