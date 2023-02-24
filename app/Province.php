<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'provinces';
    protected $fillable = ['name','name_vietnamese','id','administrative_code','region_id'];
    public $timestamps =false;
    public function Region()
    {
        return $this->belongsTo('App\Region', 'region_id', 'id')->withDefault();
    }
}
