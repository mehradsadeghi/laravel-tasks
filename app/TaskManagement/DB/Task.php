<?php

namespace App\TaskManagement\DB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
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

    public static function ofUserId($userId): Builder
    {
        return self::query()->orderBy('created_at', 'asc')->where('user_id', $userId);
    }
}
