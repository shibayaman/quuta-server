<?php

namespace App;

use \Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Thread extends Model
{
    use SoftDeletes, SoftCascadeTrait;
    
    protected $primaryKey = 'thread_id';
    protected $guarded = [];
    protected $softCascade = ['comments@update'];
    public $timestamps = false;

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'thread_id');
    }

    public function parent_comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    public function scopeWithChildCount($query)
    {
        $callback = function ($subQuery) {
            $subQuery->selectRaw('count(*)')
                ->from('comments')
                ->whereRaw('comments.thread_id = threads.thread_id');
        };
        
        $query->addSelect(['child_count' => $callback]);
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
