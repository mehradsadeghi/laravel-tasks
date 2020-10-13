<?php

namespace App\Providers;

use App\Behaviors\AuthenticateTasks;
use App\Behaviors\PreventTooManyTasksBehavior;
use App\Behaviors\PreventTamperingOtherUsersTasks;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class HeymanServiceProvider extends ServiceProvider
{
    public function boot()
    {
        require_once base_path('routes/validators.php');
        $this->security();
    }

    private function security()
    {
        AuthenticateTasks::handle();
        PreventTamperingOtherUsersTasks::handle();
        PreventTooManyTasksBehavior::handle();
    }

    /*j,static function ensureTaskIdIsValid()
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
    }*/
}
