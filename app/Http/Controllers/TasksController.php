<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TasksController extends Controller
{
    protected $rules = [
        'name' 		  => 'required|max:60',
        'description' => 'max:155',
    ];

    const stateRule = 'in:not_started,done,doing,failed,wont_do';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('tasks.index', [
            'tasks' => $this->tasksOfUser(auth()->id())->get(),
            'tasksComplete' => $this->tasksOfUser(auth()->id())->hasActiveTempTags('state', ['value', 'done'])->get(),
            'tasksInComplete' => $this->tasksOfUser(auth()->id())->hasActiveTempTags('state', ['value', 'not_started'])->get(),
            'tasksDoing' => $this->tasksOfUser(auth()->id())->hasActiveTempTags('state', ['value', 'doing'])->get(),
            'tasksFailed' => $this->tasksOfUser(auth()->id())->hasActiveTempTags('state', ['value', 'failed'])->get(),
            'tasksWont_do' => $this->tasksOfUser(auth()->id())->hasActiveTempTags('state', ['value', 'wont_do'])->get(),
        ]);
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store()
    {
        $validData = $this->validate(request(), $this->rules);
        $validData['user_id'] = auth()->id();

        $task = Task::query()->create($validData);
        $this->tagTaskCompletion('not_started', $task);

        return redirect('/tasks')->with('success', 'Task created');
    }

    public function edit($id)
    {
        $task = $this->tasksOfUser(auth()->id())->findOrFail($id);

        return view('tasks.edit', compact('task'));
    }

    public function update($id)
    {
        $validData = $this->validate(request(), ['state' => self::stateRule]);

        $task = $this->saveTask($id, $validData);

        $this->tagTaskCompletion(request('state'), $task);

        return redirect('tasks')->with('success', 'Task Updated');
    }

    public function destroy($id)
    {
        $this->tasksOfUser(auth()->id())->findOrFail($id)->delete();

        return redirect('/tasks')->with('success', 'Task Deleted');
    }

    private function tasksOfUser($userId)
    {
        return Task::query()->orderBy('created_at', 'asc')->where('user_id', $userId);
    }

    private function saveTask(int $taskId, $data)
    {
        $task = $this->tasksOfUser(auth()->id())->findOrFail($taskId);
        $task->fill($data)->save();

        return $task;
    }

    private function tagTaskCompletion($completion, $task)
    {
        $expireAt = Carbon::now()->endOfDay();
        tempTags($task)->tagIt('state', $expireAt, ['value' => $completion]);
    }
}
