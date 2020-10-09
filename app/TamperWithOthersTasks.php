<?php

namespace App;

use Illuminate\Support\Facades\Log;

class TamperWithOthersTasks
{
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