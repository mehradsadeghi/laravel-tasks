<?php

namespace App\Providers;

use App\TaskManagement\Behaviors\AuthenticateTasks;
use App\TaskManagement\Behaviors\BansSuspiciousUsers;
use App\TaskManagement\Behaviors\EnsureTaskIdIsValid;
use App\TaskManagement\Behaviors\PreventMultiTasking;
use App\TaskManagement\Behaviors\PreventTooManyTasksBehavior;
use App\TaskManagement\Behaviors\PreventTamperingOtherUsersTasks;
use App\TaskManagement\Behaviors\PreventsBannedUsersFromManagingTasks;
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
        BansSuspiciousUsers::install();
        EnsureTaskIdIsValid::install();
        PreventMultiTasking::install();
        PreventTamperingOtherUsersTasks::install();
        PreventTooManyTasksBehavior::install();
        PreventsBannedUsersFromManagingTasks::install();
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
