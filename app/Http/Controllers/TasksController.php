<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Support\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TasksController extends Controller
{
    use AuthorizesRequests,  ValidatesRequests;

    protected $rules = [
        'name' 		  => 'required|max:60',
        'description' => 'max:155',
    ];

    const stateRule = 'in:not_started,done,doing,failed,wont_do';

    public function home()
    {
        return view('home');
    }

    public function index()
    {
        $uid = auth()->id();

        return view('tasks.index', [
            'tasks' => $this->tasksOfUser($uid)->get(),
            'tasksComplete' => $this->tasksOfUser($uid)->hasActiveTags('state', ['value' => 'done'])->get(),
            'tasksInComplete' => $this->tasksOfUser($uid)
                ->where(function ($q) {
                    $q->hasActiveTags('state', ['value' => 'not_started'])
                        // this is for yesterday tasks with expired tags which are considered to be not_started for today.
                        ->orHasNotActiveTags('state');
                })
                ->get(),
            'tasksDoing' => $this->tasksOfUser($uid)->hasActiveTags('state', ['value' => 'doing'])->get(),
            'tasksFailed' => $this->tasksOfUser($uid)->hasActiveTags('state', ['value' => 'failed'])->get(),
            'tasksWont_do' => $this->tasksOfUser($uid)->hasActiveTags('state', ['value' => 'wont_do'])->get(),
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

        // By default we do not tag the tasks.
        // so if there is not tag it means they are not tried

        return redirect('/tasks')->with('success', 'Task created');
    }

    public function edit($id)
    {
        // users should not be able to edit or see each others tasks.
        $task = $this->tasksOfUser(auth()->id())->findOrFail($id);

        return view('tasks.edit', compact('task'));
    }

    public function update($id)
    {
        $validData = $this->validate(request(), ['state' => self::stateRule]);

        $task = $this->saveTask($id, $validData);

        $this->tagTaskState(request('state'), $task);

        return redirect('tasks')->with('success', 'Task Updated');
    }

    public function destroy($id)
    {
        $task = $this->tasksOfUser(auth()->id())->findOrFail($id);
        $task->unTag();
        $task->delete();

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

    private function tagTaskState($completion, $task)
    {
        $expireAt = Carbon::now()->endOfDay();
        tempTags($task)->tagIt('state', $expireAt, ['value' => $completion]);
    }
}
