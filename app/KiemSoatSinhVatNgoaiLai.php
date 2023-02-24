<?php

namespace App;

use App\Traits\PermissionDataScope;
use Illuminate\Database\Eloquent\Model;

class KiemSoatSinhVatNgoaiLai extends Model
{
    use PermissionDataScope;
    protected $guarded = [];
    public function quanHuyen()
    {
        return $this->belongsTo('App\District', 'quan_huyen_id', 'id')->select('name', 'id', 'name_vietnamese');
    }
}
