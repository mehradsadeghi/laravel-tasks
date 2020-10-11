<?php

namespace App\UserPunishment;

use Illuminate\Support\Facades\Log;

class Logger
{
    public static function bannedUserPrevented()
    {
        Log::alert('Banned user kicked out!', [
            'user_id' => auth()->id(),
            'route' => request()->route()->getName(),
        ]);
    }
}