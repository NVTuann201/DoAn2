<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DoanThanhTra extends Model
{
    protected $guarded = [];

    public function tinhThanh()
    {
        return $this->belongsToMany('App\Province');
    }

    public function loaiHinh()
    {
        return $this->belongsTo('App\Lookup', 'loai_hinh_id');
    }

    public function cheDo()
    {
        return $this->belongsTo('App\Lookup', 'che_do_id');
    }

    public function coQuanKy()
    {
        return $this->belongsTo('App\Organization', 'co_quan_ky_id');
    }

    public function coQuanThucHien()
    {
        return $this->belongsTo('App\Organization', 'co_quan_thuc_hien_id');
    }

}
