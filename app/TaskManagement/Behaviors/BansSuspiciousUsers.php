<?php

namespace App\TaskManagement\Behaviors;

use Illuminate\Support\Facades\Event;

class BansSuspiciousUsers
{
    static function install()
    {
        Event::listen('tamperWithOthersTasks', function () {
            self::banUser(120, 'Tampering with url data');
            self::youGotBannedResponse(120);
        });

        Event::listen('invalidTaskIdAttempted', function () {
            self::banUser(5, 'Requesting an invalid task id');
            self::youGotBannedResponse(5);
        });
    }

    public static function youGotBannedResponse($seconds)
    {
        return redirect()
            ->route('tasks.index')
            ->withErrors([
                'Tampering with url data will result in a temporary ban. (!_!) ',
                'You are banned for: '.$seconds.' (sec)'
            ])->throwResponse();
    }

    public static function banUser($seconds, $reason)
    {
        $user = auth()->user();
        $end = now()->addSeconds($seconds);

        tempTags($user)->tagIt('banned', $end, ['reason' => $reason]);
    }
}