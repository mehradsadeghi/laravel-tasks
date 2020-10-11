<?php

namespace App\UserPunishment;

class BanManager
{
    public static function isNotBanned()
    {
        return !((bool) tempTags(auth()->user())->getActiveTag('banned'));
    }

    public static function banUser($minute, $reason)
    {
        $user = auth()->user();
        $end = now()->addMinutes($minute);

        tempTags($user)->tagIt('banned', $end, ['reason' => $reason]);
    }
}