<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    //
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $table = 'goods';
}
