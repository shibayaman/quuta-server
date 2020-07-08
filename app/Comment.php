<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'comment_id';

    public function thread()
    {
        return $this->belongsTo(Thread::class, 'thread_id');
    }
}
