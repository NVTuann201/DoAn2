<?php

namespace App;

use App\Traits\PermissionDataScope;
use Illuminate\Database\Eloquent\Model;

class HopTacQuocTe extends Model
{
    use PermissionDataScope;
    protected $guarded = [];
    public function phanCap()
    {
        return $this->belongsTo('App\Lookup', 'phan_cap_id', 'id');
    }
    public function phanLoai()
    {
        return $this->belongsTo('App\Lookup', 'phan_loai_id', 'id');
    }
    public function danhNghia()
    {
        return $this->belongsTo('App\Lookup', 'danh_nghia_id', 'id');
    }
}
