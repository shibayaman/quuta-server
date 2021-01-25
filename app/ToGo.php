<?php

namespace App;

use Auth;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\Model;

class ToGo extends Model
{
    use SpatialTrait;

    protected $table = 'to_goes';
    protected $primaryKey = 'to_go_id';
    protected $guarded = [];
    protected $spatialFields = ['location'];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'to_go_id');
    }

    public function resolveRouteBinding($value)
    {
        return $this->where([
            'restaurant_id' => $value,
            'user_id' => Auth::id()
        ])->firstOrFail();
    }
}
