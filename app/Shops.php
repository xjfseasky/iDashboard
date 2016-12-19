<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shops extends Model
{
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $table = 'shops';
}
