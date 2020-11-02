<?php

namespace App\TaskManagement;

use App\TaskManagement\Behaviors;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Imanghafoori\HeyMan\Facades\HeyMan;

class TasksServiceProvider extends ServiceProvider
{
    public function register()
    {
        HeyMan::forRoutesLike('tasks.*', function() {
            require_once base_path('routes/validators.php');
            $this->behaviours();
        });
    }

    private function behaviours()
    {
        Behaviors\AuthenticateTasks::install();
        Behaviors\BansSuspiciousUsers::install();
        Behaviors\EnsureTaskIdIsValid::install();
        Behaviors\PreventMultiTasking::install();
        Behaviors\PreventTamperingOtherUsersTasks::install();
        Behaviors\PreventTooManyTasksBehavior::install();
        Behaviors\ItExpiresWidgetCaches::install();
        Behaviors\PreventsBannedUsersFromManagingTasks::install();
    }
}
