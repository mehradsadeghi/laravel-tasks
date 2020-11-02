<?php

namespace App\TaskManagement\Behaviors;

use Imanghafoori\HeyMan\Facades\HeyMan;

class AuthenticateTasks
{
    static function install()
    {
        HeyMan::onRoute('tasks.*')
            ->checkAuth()
            ->otherwise()
            ->redirect()->route('login');
    }
}