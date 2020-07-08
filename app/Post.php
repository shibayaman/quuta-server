<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'post_id';

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
