<?php

namespace App;

use \Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    use SoftDeletes, SoftCascadeTrait;

    protected $primaryKey = 'post_id';
    protected $guarded = [];
    protected $softCascade = ['threads@update'];

    protected $casts = [
        'like_flag' => 'bool'
    ];

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
        $postQuery->with(['goods' => function ($goodQuery) use ($user_id) {
            $goodQuery->where('user_id', $user_id);
        }]);
    }

    public static function getTimeline($sinceId, $untilId, $count, $userId = null, $callable = null)
    {
        $query = self::getBetween($sinceId, $untilId, $count)
            ->with('images')
            ->with('user');

        $userId && $query->withGoodedByUser($userId);
        $callable && $callable($query);

        return $query->get();
    }

    public static function createAndLinkImage($attributes, $images)
    {
        $post = static::create($attributes);
        $post->linkImage($images);
        return $post;
    }

    public function linkImage($images)
    {
        $images->each->linkPost($this->post_id);
    }

    public function incrementGoodCount($amount = 1)
    {
        $this->increment('good_count', $amount);
    }

    public function incrementCommentCount($amount = 1)
    {
        $this->increment('comment_count', $amount);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function threads()
    {
        return $this->hasMany(Thread::class, 'post_id');
    }

    public function goods()
    {
        return $this->hasMany(Good::class, 'post_id');
    }
    
    public function images()
    {
        return $this->hasMany(Image::class, 'post_id');
    }
}
