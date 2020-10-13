<?php


namespace App\Behaviors;


use Imanghafoori\HeyMan\Facades\HeyMan;

class AuthenticateTasks
{
    static function handle()
    {
        HeyMan::onRoute('tasks.*')
            ->checkAuth()
            ->otherwise()
            ->redirect()->route('login');
    }
}