<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Thread extends Model
{
    use SoftDeletes;
    
    protected $primaryKey = 'thread_id';
    protected $guarded = [];
    public $timestamps = false;

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'thread_id');
    }

    public function createParentComment($commentAttributes)
    {
        $comment = $this->comments()->create($commentAttributes);
        $this->linkComment($comment->comment_id);
        return $comment;
    }

    public function linkComment($comment_id)
    {
        $this->comment_id = $comment_id;
        $this->save();
    }
}
