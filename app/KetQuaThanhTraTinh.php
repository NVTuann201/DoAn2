<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KetQuaThanhTraTinh extends Model
{
    protected $guarded = [];
    protected $casts = [
        'files' => 'array',
        'files_xu_phat' => 'array',
    ];

    public function tinhThanh()
    {
        return $this->belongsTo('App\Province', 'tinh_thanh_id');
    }

    public function doanThanhTra()
    {
        return $this->belongsTo('App\DoanThanhTra', 'doan_thanh_tra_id');
    }

    public function thanhPhanDoan()
    {
        return $this->hasMany('App\ThanhPhanDoanThanhTra');
    }
}
