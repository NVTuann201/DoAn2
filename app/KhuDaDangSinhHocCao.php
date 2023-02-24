<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KhuDaDangSinhHocCao extends Model
{
    protected $guarded = [];
    public function tinhTrang(){
        return $this->belongsTo('App\Lookup', 'tinh_trang_id', 'id');
    }
}
