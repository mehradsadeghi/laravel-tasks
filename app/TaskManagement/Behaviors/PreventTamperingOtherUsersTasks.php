<?php

namespace App\TaskManagement\Behaviors;

use App\Task;
use Illuminate\Support\Facades\Log;
use Imanghafoori\HeyMan\Facades\HeyMan;

class PreventTamperingOtherUsersTasks
{
    static function install()
    {
        HeyMan::onRoute([
            'tasks.delete',
            'tasks.update',
            'tasks.edit',
        ])->thisMethodShouldAllow([self::class, 'ownsTask'])
            ->otherwise()
            ->afterCalling([self::class, 'reaction'])
            ->afterFiringEvent('tamperWithOthersTasks')
            ->weRespondFrom([self::class, 'response']);
    }

    static function ownsTask()
    {
        $id = (int) request()->route()->parameter('task');

        return auth()->id() == Task::query()->find($id)->user_id;
    }

    static function reaction()
    {
        Log::alert('someone tried to access others tasks!', [
            'user_id' => auth()->id(),
            'route' => request()->route()->getName(),
            'task_id' => request()->route()->parameter('task')
        ]);
    }

    static function response()
    {
        return redirect()
            ->back()
            ->withErrors('Tampering with url data will result in a temporary ban.');
    }
}