<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BangTinTongHop extends Model
{
    protected $guarded = [];
    public function phanCap(){
        return $this->belongsTo('App\Lookup', 'phan_cap_id', 'id');
    }
    public function thongSo(){
        return $this->belongsTo('App\ThongSo', 'thong_so_id', 'id');
    }
}
