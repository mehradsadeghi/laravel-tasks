<?php

namespace App\Providers;

use App\TaskManagement\Behaviors;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class TasksServiceProvider extends ServiceProvider
{
    public function boot()
    {
        require_once base_path('routes/validators.php');
        $this->behaviours();
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
