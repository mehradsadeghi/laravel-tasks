<?php

namespace App\UserPunishment;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Imanghafoori\HeyMan\Facades\HeyMan;

class UserPunishmentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->stopBannedUsers();
        $this->banBadUsers();
    }

    private function stopBannedUsers()
    {
        $logger = function () {
            Log::alert('Banned user kicked out!', [
                'user_id' => auth()->id(),
                'route' => request()->route()->getName(),
            ]);
        };

        HeyMan::onRoute([
            'tasks.delete',
            'tasks.update',
            'tasks.create',
            'tasks.store',
            'tasks.edit',
        ])->thisMethodShouldAllow([BanManager::class, 'isNotBanned'])
            ->otherwise()
            ->afterCalling($logger)
            ->weRespondFrom([Responses::class, 'youAreBanned']);
    }

    private function banBadUsers()
    {
        Event::listen(['tamperWithOthersTasks', 'invalidTaskIdAttempted'], function () {
            BanManager::banUser(2, 'Accessing other users data.');
            Responses::youGotBanned()->throwResponse();
        });
    }
}