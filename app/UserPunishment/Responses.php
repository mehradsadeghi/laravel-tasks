<?php

namespace App\UserPunishment;

class Responses
{
    public static function youAreBanned()
    {
        return redirect()
            ->route('tasks.index')
            ->withErrors('You are temporarily banned. You can see your tasks list but not manage them.');
    }

    public static function youGotBanned()
    {
        return redirect()
            ->route('tasks.index')
            ->withErrors('Tampering with url data will result in a temporary ban. (!_!) ');
    }
}