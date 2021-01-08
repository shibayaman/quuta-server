<?php

namespace App;

use App\Post;
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
    protected $hidden = [
        'password',
        'password_reset_token',
        'token_expires_at',
        'password_updated_at'
    ];
    public $incrementing = false;

    public function homeTimeline($sinceId = null, $untilId = null, $count = null)
    {
        return Post::getTimeline($sinceId, $untilId, $count, $this->user_id, function ($query) {
            $userIds = $this->followings()->pluck('follow_user_id');
            $userIds[] = $this->user_id;
            $query->whereIn('user_id', $userIds);
        });
    }

    public function userTimeline($userId, $sinceId = null, $untilId = null, $count = null)
    {
        return Post::getTimeline($sinceId, $untilId, $count, $this->user_id, function ($query) use ($userId) {
            $query->where('user_id', $userId);
        });
    }

    public function restaurantTimeline($restaurantId, $sinceId = null, $untilId = null, $count = null)
    {
        return Post::getTimeline($sinceId, $untilId, $count, $this->user_id, function ($query) use ($restaurantId) {
            $query->where('restaurant_id', $restaurantId);
        });
    }

    public function incrementGoodCount($amount = 1)
    {
        $this->increment('good_count', $amount);
    }

    public function incrementFollowerCount($amount = 1)
    {
        $this->increment('follower_count', $amount);
    }
    
    public function incrementFollowingCount($amount = 1)
    {
        $this->increment('following_count', $amount);
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

    public function followers()
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
