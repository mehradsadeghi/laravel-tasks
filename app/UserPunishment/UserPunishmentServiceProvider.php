<?php

namespace App\UserPunishment;

use Illuminate\Support\Facades\Event;
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
        HeyMan::onRoute([
            'tasks.create',
            'tasks.store',
            'tasks.edit',
            'tasks.update',
            'tasks.delete',
        ])->thisMethodShouldAllow([BanManager::class, 'isNotBanned'])
            ->otherwise()
            ->afterCalling([Logger::class, 'bannedUserPrevented'])
            ->weRespondFrom([Responses::class, 'youAreBanned']);
    }

    private function banBadUsers()
    {
        Event::listen(['tamperWithOthersTasks', 'invalidTaskIdAttempted'], function () {
            BanManager::banUser(2, 'Tampering with url data');
            Responses::youGotBanned()->throwResponse();
        });
    }
}