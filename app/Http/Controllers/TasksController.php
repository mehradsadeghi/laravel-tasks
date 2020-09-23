<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TasksController extends Controller
{
    const completeTag = 'complete';

    protected $rules = [
        'name' 		  => 'required|max:60',
        'description' => 'max:155',
        'completed'   => 'in:0,1',
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('tasks.index', [
            'tasks' => $this->tasksOfUser(auth()->id())->get(),
            'tasksComplete' => $this->tasksOfUser(auth()->id())->hasActiveTempTags(self::completeTag)->get(),
            'tasksInComplete' => $this->tasksOfUser(auth()->id())->hasNotActiveTempTags(self::completeTag)->get(),
        ]);
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $validData = $this->validate($request, $this->rules);
        $validData['user_id'] = auth()->id();
        Task::query()->create($validData);

        return redirect('/tasks')->with('success', 'Task created');
    }

    public function edit($id)
    {
        $task = $this->tasksOfUser(auth()->id())->findOrFail($id);

        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, $id)
    {
        $request = $this->validate($request, $this->rules);

        $task = $this->saveTask($id, $request);

        $this->tagTaskCompletion(request('completed'), $task);

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

    private function tagTaskCompletion($completion, $task): void
    {
        if ($completion == '1') {
            $expireAt = Carbon::now()->endOfDay();
            tempTags($task)->tagIt(self::completeTag, $expireAt);
        } else {
            tempTags($task)->unTag(self::completeTag);
        }
    }
}
