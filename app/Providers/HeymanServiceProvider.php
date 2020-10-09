<?php

namespace App\Providers;

use App\InvalidTaskIdBehavior;
use App\Policies\TaskPolicy;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Log;
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
        $this->ensureTaskIdIsValid();
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
        $logger = function () {
            Log::alert('someone tried to access a others tasks!', [
                'user_id' => auth()->id(),
                'route' => request()->route()->getName(),
                'task_id' => request()->route()->parameter('task')
            ]);

            event('tamperWithOthersTasks');
        };

        HeyMan::onRoute([
            'tasks.delete',
            'tasks.update',
            'tasks.edit',
        ])->thisMethodShouldAllow([TaskPolicy::class, 'ownsTask'])
            ->otherwise()
            ->afterCalling($logger)
            ->redirect()->back()
            ->withErrors('Tampering with url data will result in a temporary ban.');
    }

    private function ensureTaskIdIsValid()
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