<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewAccountToken extends Model
{
    protected $primaryKey = 'email';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;
}
