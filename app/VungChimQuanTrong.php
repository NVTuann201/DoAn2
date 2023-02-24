<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VungChimQuanTrong extends Model
{
    protected $guarded = [];
    public function tinhTrang(){
        return $this->belongsTo('App\Lookup', 'tinh_trang_id', 'id');
    }
    public function khuBaoTon(){
        return $this->belongsTo('App\ProtectedArea', 'khu_bao_ton_id', 'id');
    }
    public function doQuanTrong(){
        return $this->belongsTo('App\Lookup', 'muc_do_quan_trong_id', 'id');
    }
}
