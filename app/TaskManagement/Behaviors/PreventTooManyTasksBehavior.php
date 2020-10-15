<?php

namespace App\TaskManagement\Behaviors;

use App\Task;
use Imanghafoori\HeyMan\Facades\HeyMan;

class PreventTooManyTasksBehavior
{
    const affordable = 10;

    public static function affordableNumber()
    {
        return Task::getCount(auth()->id()) < self::affordable;
    }

    public static function install()
    {
        HeyMan::onRoute([
            'tasks.create',
            'tasks.store',
        ])->thisMethodShouldAllow([self::class, 'affordableNumber'])
            ->otherwise()
            ->weRespondFrom([self::class, 'response']);
    }

    public static function response()
    {
        return redirect()
            ->route('tasks.index')
            ->withErrors('You should not do too many tasks a day, Take a rest.');
    }
}