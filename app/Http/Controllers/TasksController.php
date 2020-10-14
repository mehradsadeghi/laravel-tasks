<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Support\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TasksController extends Controller
{
    use AuthorizesRequests;

    public function home()
    {
        return view('home');
    }

    public function index()
    {
        $userId = auth()->id();

        return view('tasks.index', [
            'tasks' => $this->tasksOfUser($userId)->get(),
            'tasksComplete' => $this->getTasksWithTag($userId, 'done'),
            'tasksInComplete' => $this->tasksOfUser($userId)
                ->where(function ($q) {
                    $q->hasActiveTags('state', ['value' => 'not_started'])
                        // this is for yesterday tasks with expired tags which are considered to be not_started for today.
                        ->orHasNotActiveTags('state');
                })
                ->get(),
            'tasksDoing' => $this->getTasksWithTag($userId, 'doing'),
            'tasksFailed' => $this->getTasksWithTag($userId, 'failed'),
            'tasksWont_do' => $this->getTasksWithTag($userId, 'wont_do'),
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

        return redirect('/tasks')->with('success', 'Task created');
    }

    public function edit($id)
    {
        // users should not be able to edit or see each others tasks.
        $task = Task::query()->find($id);

        return compact('task');
    }

    public function update($id)
    {
        // validation: routes/validators.php
        $validData = request()->only('state');

        $task = $this->saveTask($id, $validData);

        $this->tagTaskState(request('state'), $task);

        return redirect('tasks')->with('success', 'Task Updated');
    }

    public function destroy($id)
    {
        $task = Task::query()->find($id);
        tempTags($task)->unTag();
        $task->delete();

        return redirect('/tasks')->with('success', 'Task Deleted');
    }

    private function tasksOfUser($userId)
    {
        return Task::query()->orderBy('created_at', 'asc')->where('user_id', $userId);
    }

    private function saveTask(int $taskId, $data)
    {
        $task = Task::query()->findOrFail($taskId);

        $task->fill($data)->save();

        return $task;
    }

    private function tagTaskState($completion, $task)
    {
        $expireAt = Carbon::now()->endOfDay();
        $payload = ['value' => $completion, 'at' => now()->format('H:i:s')];
        tempTags($task)->tagIt('state', $expireAt, $payload);
    }

    private function getTasksWithTag($uid, string $tag)
    {
        return $this->tasksOfUser($uid)->hasActiveTags('state', ['value' => $tag])->get();
    }
}
