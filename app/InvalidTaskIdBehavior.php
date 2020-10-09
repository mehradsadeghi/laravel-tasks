<?php

namespace App;

use Illuminate\Support\Facades\Log;

class InvalidTaskIdBehavior
{
    public static function reaction()
    {
        Log::alert('someone tried to access a non-existing task!', [
            'user_id' => auth()->id(),
            'route' => request()->route()->getName(),
            'task_id' => request()->route()->parameter('task')
        ]);
    }

    public static function response()
    {
        return redirect()->route('tasks.index');
    }
}
