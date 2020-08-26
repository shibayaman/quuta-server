<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    protected $primaryKey = 'good_id';
    protected $guarded = [];
    public $incrementing = false;
    public $timestamps = false;

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($good) {
            $good->post->incrementGoodCount();
        });
    }
}
