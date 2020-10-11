<?php

namespace App\Providers;

use App\InvalidTaskIdBehavior;
use App\Policies\TaskPolicy;
use App\TamperWithOthersTasks;
use Imanghafoori\HeyMan\Facades\HeyMan;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

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
//        self::ensureTaskIdIsValid();
        $this->preventTamperingOtherUsersTasks();
        $this->preventTooManyTasks();
    }

    private function auth()
    {
        HeyMan::onRoute('tasks.*')
            ->checkAuth()
            ->otherwise()
            ->redirect()->route('login');
    }

    private function preventTooManyTasks()
    {
        HeyMan::onRoute([
            'tasks.create',
            'tasks.store',
        ])->thisMethodShouldAllow([TaskPolicy::class, 'affordableNumber'])
            ->otherwise()
            ->redirect()->route('tasks.index')
            ->withErrors('You should not do too many tasks a day, Take a rest.');
    }

    private function preventTamperingOtherUsersTasks()
    {
        HeyMan::onRoute([
            'tasks.delete',
            'tasks.update',
            'tasks.edit',
        ])->thisMethodShouldAllow([TaskPolicy::class, 'ownsTask'])
            ->otherwise()
            ->afterCalling([TamperWithOthersTasks::class, 'reaction'])
            ->afterFiringEvent('tamperWithOthersTasks')
            ->weRespondFrom([TamperWithOthersTasks::class, 'response']);
    }

    static function ensureTaskIdIsValid()
    {
        HeyMan::onRoute([
            'tasks.delete',
            'tasks.update',
            'tasks.edit',
        ])->thisMethodShouldAllow([TaskPolicy::class, 'taskExists'])
            ->otherwise()
            ->afterCalling([InvalidTaskIdBehavior::class, 'reaction'])
            ->afterFiringEvent('invalidTaskIdAttempted')
            ->weRespondFrom([InvalidTaskIdBehavior::class, 'response']);
    }
}
