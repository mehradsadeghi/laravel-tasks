<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Imanghafoori\HeyMan\Facades\HeyMan;

class HeymanServiceProvider extends ServiceProvider
{
    public function boot()
    {
        HeyMan::whenYouHitRouteName('task.*')
            ->checkAuth()
            ->otherwise()
            ->redirect()->route('login');

    }
}