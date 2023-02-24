<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KhuDuTruSinhQuyen extends Model
{
    protected $guarded = [];
    public function tinhTrang(){
        return $this->belongsTo('App\Lookup', 'tinh_trang_id', 'id');
    }
    public function khuBaoTon(){
        return $this->belongsTo('App\ProtectedArea', 'khu_bao_ton_id', 'id');
    }
}
