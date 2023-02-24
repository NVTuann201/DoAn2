<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KetQuaQuanTrac extends Model
{
    protected $guarded = [];
    public function diemQuanTrac(){
        return $this->belongsTo('App\DiemQuanTrac', 'diem_quan_trac_id', 'id');
    }
    public function thongSo(){
        return $this->belongsTo('App\ThongSo', 'thong_so_id', 'id');
    }
}
