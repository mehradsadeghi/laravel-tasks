<?php


namespace App\TaskManagement\Behaviors;


use App\TaskManagement\DB\Task;
use Imanghafoori\HeyMan\Facades\HeyMan;

class PreventMultiTasking
{
    public static $task;

    public static function install()
    {
        HeyMan::onRoute([
            'tasks.update',
        ])->thisMethodShouldAllow([self::class, 'userHasDoingTask'])
            ->otherwise()
            ->afterCalling([self::class, 'reaction'])
            ->afterFiringEvent('multiTaskPrevented')
            ->weRespondFrom([self::class, 'response']);
    }

    public static function userHasDoingTask()
    {
        $state = request('state');

        if ($state !== 'doing') {
            return true;
        }

        $taskId = (int) request()->route()->parameter('task');

        self::$task = $task = Task::hasActiveTags('state', ['value' => 'doing'])
            ->where('id', '!=', $taskId)
            ->where('user_id', auth()->id())
            ->first();

        if (! $task) {
            return true;
        }

        return false;
    }

    public static function reaction()
    {

    }

    public static function response()
    {
        $name = (self::$task)->name;
        return redirect()
            ->back()
            ->withErrors('You can not start a new task ('. $name.') unless you finish the previous one.');
    }
}