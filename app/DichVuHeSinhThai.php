<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DichVuHeSinhThai extends Model
{
    protected $guarded = [];
    public function phanLoai(){
        return $this->belongsTo('App\Lookup', 'phan_loai_id', 'id');
    }
    public function heSinhThai(){
        return $this->belongsToMany('App\HeSinhThai', 'he_sinh_thai_dich_vus', 'dich_vu_he_sinh_thai_id', 'he_sinh_thai_id');
    }
}
