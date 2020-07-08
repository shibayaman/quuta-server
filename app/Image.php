<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $primaryKey = 'image_id';
    public $incrementing = false;

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
