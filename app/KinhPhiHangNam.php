<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KinhPhiHangNam extends Model
{
    protected $guarded = [];
    public function nguonKinhPhi()
    {
        return $this->belongsTo('App\Lookup', 'nguon_kinh_phi_id', 'id');
    }
    public function tinhThanh()
    {
        return $this->belongsTo('App\Province', 'tinh_thanh_id', 'id');
    }
    public function quanHuyen()
    {
        return $this->belongsTo('App\District', 'quan_huyen_id', 'id')->select('name', 'id', 'name_vietnamese');
    }
}
