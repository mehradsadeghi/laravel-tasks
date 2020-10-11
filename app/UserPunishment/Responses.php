<?php

namespace App\UserPunishment;

class Responses
{
    public static function youAreBanned()
    {
        $tag = tempTags(auth()->user())->getActiveTag('banned');
        $reason = $tag->getPayload('reason');

        return redirect()
            ->route('tasks.index')
            ->withErrors([
                'You are temporarily banned. Because of: "'.$reason.'"',
                'You can see your tasks list but not manage them.',
                'Your ban will expire at: '.(string)$tag->expired_at
            ]);
    }

    public static function youGotBanned()
    {
        $tag = tempTags(auth()->user())->getActiveTag('banned');

        return redirect()
            ->route('tasks.index')
            ->withErrors([
                'Tampering with url data will result in a temporary ban. (!_!) ',
                'You are banned until: '.$tag->expired_at
                ]);
    }
}