<?php

namespace App\TaskManagement\Behaviors;

use App\Task;
use Illuminate\Support\Facades\Log;
use Imanghafoori\HeyMan\Facades\HeyMan;

class EnsureTaskIdIsValid
{
    public static function install()
    {
        HeyMan::onRoute([
            'tasks.delete',
            'tasks.update',
            'tasks.edit',
        ])->thisMethodShouldAllow([self::class, 'isValidTaskId'])
            ->otherwise()
            ->afterCalling([self::class, 'reaction'])
            ->afterFiringEvent('invalidTaskIdAttempted')
            ->weRespondFrom([self::class, 'response']);
    }

    public static function reaction()
    {
        Log::alert('Someone tried to access a non-existing task!', [
            'user_id' => auth()->id(),
            'route' => request()->route()->getName(),
            'task_id' => request()->route()->parameter('task')
        ]);
    }

    public static function response()
    {
        return redirect()
            ->back()
            ->withErrors('Tampering with url data will result in a temporary ban.');
    }

    public static function isValidTaskId()
    {
        $id = (int) request()->route()->parameter('task');

        return is_numeric($id) && (bool) Task::query()->find($id);
    }
}