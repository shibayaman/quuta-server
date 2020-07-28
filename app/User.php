<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use SoftDeletes;
    
    protected $primaryKey = 'user_id';
    protected $keyType = 'string';
    public $incrementing = false;

    public function homeTimeline($sinceId = null, $untilId = null, $count = null)
    {
        return Post::getBetween($sinceId, $untilId, $count)
            ->whereIn('user_id', $this->following()->pluck('follow_user_id'))
            ->withGoodedByUser($this->user_id)
            ->get();
    }

    public function userTimeline($user_id, $sinceId = null, $untilId = null, $count = null)
    {
        return Post::getBetween($sinceId, $untilId, $count)
            ->where('user_id', $user_id)
            ->withGoodedByUser($this->user_id)
            ->get();
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

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

    public function following()
    {
        return $this->hasMany(Follow::class, 'user_id');
    }

    public function followed()
    {
        return $this->hasMany(Follow::class, 'follow_user_id');
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
