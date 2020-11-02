<?php

namespace App\TaskManagement\Behaviors;

use App\TaskManagement\DB\TaskRepo;
use Illuminate\Support\Facades\Event;

class ItExpiresWidgetCaches
{
    public static function install()
    {
        Event::listen(['task.changing_state'], function($task, $newState) {
            $state = TaskRepo::getState($task);

            self::expireState($state);
            self::expireState($newState);
            self::expireState('all');
        });

        Event::listen(['task.deleting'], function($task) {
            $state = TaskRepo::getState($task);
            self::expireState($state);
            self::expireState('all');
        });

        Event::listen(['task.created'], function($task, $newState) {
            self::expireState($newState);
            self::expireState('all');
        });
    }

    private static function expireState(string $state): void
    {
        cache()->forget('task_list_'.auth()->id().'_'.$state);
    }

}