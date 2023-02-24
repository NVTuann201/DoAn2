<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class GiayPhepDaDangSinhHoc extends Model
{
    protected $guarded = [];
    public function loaiGiayPhep(){
        return $this->belongsTo('App\Lookup', 'giay_phep_id', 'id');
    }
    public function loaiCap(){
        return $this->belongsTo('App\Lookup', 'loai_cap_phep_id', 'id');
    }
    public function doiTuong(){
        return $this->belongsTo('App\Lookup', 'doi_tuong_id', 'id');
    }
    public function mucDich(){
        return $this->belongsTo('App\Lookup', 'muc_dich_id', 'id');
    }
}
