<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $primaryKey = 'follow_id';
    protected $guarded = [];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function follow_user()
    {
        return $this->belongsTo(User::class, 'follow_user_id');
    }
    
    public function posts_of_follow_user()
    {
        return $this->hasMany(Post::class, 'user_id', 'follow_user_id');
    }
}
