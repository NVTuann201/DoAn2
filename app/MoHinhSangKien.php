<?php

namespace App;

use App\Traits\PermissionDataScope;
use Illuminate\Database\Eloquent\Model;

class MoHinhSangKien extends Model
{
    use PermissionDataScope;
    protected $guarded = [];
    public function phanLoai()
    {
        return $this->belongsTo('App\Lookup', 'phan_loai_id', 'id');
    }
    public function hinhThuc()
    {
        return $this->belongsTo('App\Lookup', 'hinh_thuc_id', 'id');
    }
    public function quanHuyen()
    {
        return $this->belongsTo('App\District', 'quan_huyen_id', 'id')->select('name', 'id', 'name_vietnamese');
    }
}
