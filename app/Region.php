<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'regions';
    protected $fillable = ['name','name_vietnamese','id'];
    public $timestamps =false;
}
