<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Phaza\LaravelPostgis\Eloquent\PostgisTrait;

class HeSinhThai extends Model
{
    use PostgisTrait;
    protected $guarded = [];
    protected $postgisFields = [
        'geom',
    ];



    public function heSinhThai()
    {
        return $this->belongsTo('App\Lookup', 'he_sinh_thai_lookup_id', 'id');
    }
    public function diaDiem()
    {
        return $this->belongsTo('App\ProtectedArea', 'dia_diem_id', 'id');
    }
    public function phanLoai()
    {
        return $this->belongsTo('App\Lookup', 'phan_loai_id', 'id');
    }
    public function nguonGoc()
    {
        return $this->belongsTo('App\Lookup', 'nguon_goc_id', 'id');
    }
    public function phanLoaiRung()
    {
        return $this->belongsTo('App\Lookup', 'phan_loai_rung_id', 'id');
    }
    public function phanLoaiHeSinhThai()
    {
        return $this->belongsTo('App\Lookup', 'phan_loai_he_sinh_thai_id', 'id');
    }

    public function getQuanHuyenAttribute($value)
    {
        if (isset($value))
            return District::whereIn('id', (array)json_decode($value))->get();
    }
}
