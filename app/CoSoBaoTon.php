<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Phaza\LaravelPostgis\Eloquent\PostgisTrait;

class CoSoBaoTon extends Model
{
    use PostgisTrait;
    protected $guarded = [];
    protected $postgisFields = [
        'geom',
    ];
    public function diaDiem()
    {
        return $this->belongsTo('App\ProtectedArea', 'dia_diem_id', 'id');
    }
    public function loaiHinh()
    {
        return $this->belongsTo('App\Lookup', 'loai_hinh_id', 'id');
    }
    public function loaiHinhToChuc()
    {
        return $this->belongsTo('App\Lookup', 'loai_hinh_to_chuc_id', 'id');
    }
    public function quanLy()
    {
        return $this->belongsTo('App\Lookup', 'quan_ly_id', 'id');
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
