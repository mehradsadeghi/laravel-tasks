<?php

namespace App\TaskManagement\DB;

use Illuminate\Database\Eloquent\Model;
use Imanghafoori\Tags\Traits\hasTempTags;

class Task extends Model
{
    use hasTempTags;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'name', 'description'];

    public static function getCount($userId)
    {
        return Task::query()->where('user_id', $userId)->count();
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function whereStateIs($state)
    {
        return $this->hasActiveTags('state', ['value' => $state])->get();
    }

    public static function ofUserId($userId): self
    {
        return self::query()->orderBy('created_at', 'asc')->where('user_id', $userId);
    }

    public static function remove($id)
    {
        $task = Task::query()->find($id);
        tempTags($task)->unTag();

        return $task->delete();
    }

    public function setState(string $state)
    {
        $expireAt = now()->endOfDay();
        $payload = ['value' => $state, 'at' => now()->format('H:i:s')];
        tempTags($this)->tagIt('state', $expireAt, $payload);
    }

    public function withDefaultState()
    {
        // this is for yesterday tasks with expired tags
        // which are considered to be not_started for today.
        return $this->where(function ($q) {
            $q->whereStateIs('not_started')->orHasNotActiveTags('state');
        });
    }
}
