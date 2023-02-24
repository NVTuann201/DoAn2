<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KetQuaThanhTraCoSo extends Model
{
    protected $guarded = [];
    protected $casts = [
        'files' => 'array',
        'files_xu_phat' => 'array',
    ];

    public function coSo()
    {
        return $this->morphTo('co_so', 'co_so_type', 'co_so_id');
    }
    public function coQuanKy()
    {
        return $this->belongsTo('App\Organization', 'co_quan_ky_id');
    }
    public function doanThanhTra()
    {
        return $this->belongsTo('App\DoanThanhTra', 'doan_thanh_tra_id');
    }
}
