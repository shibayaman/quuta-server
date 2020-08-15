<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $primaryKey = 'image_id';
    protected $guarded = [];
    public $timestamps = false;

    public function linkPost($postId)
    {
        $this->post_id = $postId;
        $this->save();
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
