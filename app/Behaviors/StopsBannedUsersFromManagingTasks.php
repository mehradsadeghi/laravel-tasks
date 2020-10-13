<?php

namespace App\Behaviors;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Imanghafoori\HeyMan\Facades\HeyMan;

class StopsBannedUsersFromManagingTasks
{
    public static function install()
    {
        HeyMan::onRoute([
            'tasks.create',
            'tasks.store',
            'tasks.edit',
            'tasks.update',
            'tasks.delete',
        ])->thisMethodShouldAllow([self::class, 'isNotBanned'])
            ->otherwise()
            ->afterCalling([self::class, 'logBannedUserPrevented'])
            ->weRespondFrom([self::class, 'youAreBanned']);
    }

    public static function youAreBanned()
    {
        $tag = tempTags(auth()->user())->getActiveTag('banned');
        $reason = $tag->getPayload('reason');

        return redirect()
            ->route('tasks.index')
            ->withErrors([
                'You are temporarily banned. Because of: "'.$reason.'"',
                'You can see your tasks list but not manage them.',
                'Your ban will expire in: '.Carbon::now()->diffInSeconds($tag->expired_at) .' sec'
            ]);
    }

    public static function isNotBanned()
    {
        return !((bool) tempTags(auth()->user())->getActiveTag('banned'));
    }

    public static function logBannedUserPrevented()
    {
        Log::alert('Banned user kicked out!', [
            'user_id' => auth()->id(),
            'route' => request()->route()->getName(),
        ]);
    }
}