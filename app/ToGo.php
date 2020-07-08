<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ToGo extends Model
{
    protected $table = 'to_goes';
    protected $primaryKey = 'to_go_id';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'to_go_id');
    }
}
