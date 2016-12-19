<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shopsku extends Model
{
    //
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $table = 'shopsku';
}
