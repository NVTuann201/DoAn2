<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Phaza\LaravelPostgis\Eloquent\PostgisTrait;

class DiemQuanTrac extends Model
{
    use PostgisTrait;
    protected $guarded = [];
    protected $postgisFields = [
        'geom',
    ];

    public function khuBaoTon()
    {
        return $this->belongsTo('App\ProtectedArea', 'khu_bao_ton_id', 'id');
    }
    public function loaiHinh()
    {
        return $this->belongsTo('App\LoaiHinhQuanTrac', 'loai_hinh_id', 'id');
    }
}
