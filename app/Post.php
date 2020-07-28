<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'post_id';

    public function scopeGetBetween($query, $sinceId, $untilId, $count = null)
    {
        $keyName = $this->getQualifiedKeyName();

        if ($sinceId) {
            $query->where($keyName, '>', $sinceId);
        }

        if ($untilId) {
            $query->where($keyName, '<=', $untilId);
        }

        if ($count) {
            $query->limit($count);
            $query->orderBy($keyName, 'desc');
        }
    }

    public function scopeWithGoodedByUser($postQuery, $user_id)
    {
        $postQuery->with(['good' => function ($goodQuery) use ($user_id) {
            $goodQuery->where('user_id', $user_id);
        }]);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function thread()
    {
        return $this->hasMany(Thread::class, 'post_id');
    }

    public function good()
    {
        return $this->hasMany(Good::class, 'post_id');
    }
    
    public function image()
    {
        return $this->hasMany(Image::class, 'post_id');
    }
}
