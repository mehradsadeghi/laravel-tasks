<?php

namespace App;

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

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
