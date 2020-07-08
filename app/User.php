<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    
    protected $primaryKey = 'user_id';
    protected $keyType = 'string';
    public $incrementing = false;

    public function sex()
    {
        return $this->belongsTo(Sex::class, 'sex_id');
    }

    public function post()
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    public function good()
    {
        return $this->hasMany(Good::class, 'user_id');
    }

    public function follow()
    {
        return $this->hasMany(Follower::class, 'user_id');
    }

    public function comment()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    public function to_go()
    {
        return $this->hasMany(ToGo::class, 'user_id');
    }
}
