<?php

namespace App\Providers;

use App\Http\Controllers\TasksController;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Imanghafoori\HeyMan\Facades\HeyMan;

class HeymanServiceProvider extends ServiceProvider
{
    public function boot()
    {
        require_once base_path('routes/validators.php');
        $this->auth();
        $this->security();
    }

    private function security()
    {
        HeyMan::onRoute([
            'tasks.delete',
            'tasks.update',
            'tasks.edit',
        ])->thisMethodShouldAllow([TasksController::class, 'ownsTask'])
            ->otherwise()
            ->redirect()->back();
    }

    private function auth()
    {
        HeyMan::onRoute('tasks.*')
            ->checkAuth()
            ->otherwise()
            ->redirect()->route('login');

    }
}