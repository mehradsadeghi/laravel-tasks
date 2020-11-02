<?php

namespace App\TaskManagement\DB;

class TaskRepo
{
    const defaultState = 'not_started';

    const state = 'state';

    public static function withDefaultState($userId)
    {
        $q = Task::ofUserId($userId);
        // This is for yesterday tasks with expired tags
        // which are considered to be not_started for today.
        return $q->where(function ($q) {
            $q->hasActiveTags(self::state, ['value' => self::defaultState])
                ->orHasNotActiveTags(self::state);
        });
    }

    public static function remove($id)
    {
        $task = Task::query()->find($id);

        event('task.deleting', [$task]);

        return [$task, $task->delete()];
    }

    public static function changeState($id, string $state)
    {
        $task = Task::query()->find($id);

        $payload = ['value' => $state, 'at' => now()->format('H:i:s')];

        event('task.changing_state', [$task, $state]);

        self::setState($task, $payload);
    }

    public static function saveNew($data, $uid)
    {
        $data['user_id'] = $uid;

        $task = Task::query()->create($data);

        self::setState($task, ['value' => self::defaultState]);

        event('task.created', [$task, self::defaultState]);

        return $task;
    }

    public static function find($userId, $state = null)
    {
        $q = Task::ofUserId($userId);

        $state && $q->hasActiveTags(self::state, ['value' => $state]);

        return $q;
    }

    public static function getState($task): string
    {
        return optional(tempTags($task)->getActiveTag(self::state))->getPayload('value') ?? self::defaultState;
    }

    private static function setState($task, array $payload)
    {
        tempTags($task)->tagIt(self::state, now()->endOfDay(), $payload);
    }
}