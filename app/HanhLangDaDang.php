<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HanhLangDaDang extends Model
{
    protected $guarded = [];
    public function khuBaoTon(){
        return $this->belongsToMany('App\ProtectedArea', 'hanh_lang_khu_bao_tons', 'hanh_lang_id','khu_bao_ton_id');
    }
    public function tinhTrang(){
        return $this->belongsTo('App\Lookup', 'tinh_trang_id', 'id');
    }
    public function quanLy(){
        return $this->belongsTo('App\Lookup', 'phan_cap_quan_ly_id', 'id');
    }
}
