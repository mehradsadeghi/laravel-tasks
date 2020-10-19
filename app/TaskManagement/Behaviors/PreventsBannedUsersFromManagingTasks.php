<?php

namespace App\TaskManagement\Behaviors;

use Illuminate\Support\Facades\Log;
use Imanghafoori\HeyMan\Facades\HeyMan;

class PreventsBannedUsersFromManagingTasks
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
            ->weRespondFrom([self::class, 'youAreBannedResponse']);
    }

    public static function youAreBannedResponse()
    {
        $tag = tempTags(auth()->user())->getActiveTag('banned');
        $reason = $tag->getPayload('reason');

        return redirect()
            ->route('tasks.index')
            ->withErrors([
                'You are temporarily banned. Because of: "'.$reason.'"',
                'You can see your tasks list but not manage them.',
                'Your ban will expire in: '.now()->diffInSeconds($tag->expired_at).' sec'
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