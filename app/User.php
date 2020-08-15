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
        return $this->getPosts($sinceId, $untilId, $count, function ($query) {
            $query->whereIn('user_id', $this->followings()->pluck('follow_user_id'));
        });
    }

    public function userTimeline($userId, $sinceId = null, $untilId = null, $count = null)
    {
        return $this->getPosts($sinceId, $untilId, $count, function ($query) use ($userId) {
            $query->where('user_id', $userId);
        });
    }

    public function getPosts($sinceId, $untilId, $count, callable $callable = null)
    {
        $query = Post::getBetween($sinceId, $untilId, $count)
            ->withGoodedByUser($this->user_id)
            ->with('images')
            ->with('user');

        $callable && $callable($query);

        return $query->get();
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

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    public function goods()
    {
        return $this->hasMany(Good::class, 'user_id');
    }

    public function followings()
    {
        return $this->hasMany(Follow::class, 'user_id');
    }

    public function followeds()
    {
        return $this->hasMany(Follow::class, 'follow_user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    public function to_goes()
    {
        return $this->hasMany(ToGo::class, 'user_id');
    }
    
    public function images()
    {
        return $this->hasMany(Image::class, 'user_id');
    }
}
