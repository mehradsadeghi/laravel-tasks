<?php

namespace App\Providers;

use App\Behaviors\AuthenticateTasks;
use App\Behaviors\BansSuspeciousUsers;
use App\Behaviors\EnsureTaskIdIsValid;
use App\Behaviors\PreventMultiTasking;
use App\Behaviors\PreventTooManyTasksBehavior;
use App\Behaviors\PreventTamperingOtherUsersTasks;
use App\Behaviors\StopsBannedUsersFromManagingTasks;
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
        AuthenticateTasks::install();
        BansSuspeciousUsers::install();
        EnsureTaskIdIsValid::install();
        PreventMultiTasking::install();
        PreventTamperingOtherUsersTasks::install();
        PreventTooManyTasksBehavior::install();
        StopsBannedUsersFromManagingTasks::install();
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
