<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChiThi extends Model
{
    protected $guarded = [];
    public function nhomChiThi(){
        return $this->belongsTo('App\NhomChiThi');
    }
}
