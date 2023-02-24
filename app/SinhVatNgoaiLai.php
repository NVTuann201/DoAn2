<?php

namespace App;

use App\Traits\PermissionDataScope;
use Illuminate\Database\Eloquent\Model;

class SinhVatNgoaiLai extends Model
{
    use PermissionDataScope;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->isFieldTinhThanhIsJson = true;
    }
    protected $guarded = [];
    public function phanLoai()
    {
        return $this->belongsTo('App\Lookup', 'phan_loai_id', 'id');
    }
    public function nguyCo()
    {
        return $this->belongsTo('App\Lookup', 'nguy_co_id', 'id');
    }
    public function diaDiem()
    {
        return $this->belongsToMany('App\ProtectedArea', 'dia_diem_sinh_vat_ngoai_lais', 'sinh_vat_ngoai_lai_id', 'dia_diem_id');
    }
}
