<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $primaryKey = 'follow_id';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function follow_user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
