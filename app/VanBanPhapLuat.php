<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class VanBanPhapLuat extends Model
{
    protected $guarded = [];
    protected $appends = ['dinh_kem'];
    public function getDinhKemAttribute()
    {
        return DB::table('media')->whereIn('id', json_decode($this->files))->select('id', 'name', 'disk', 'size')->get();
    }
    public function phanLoai()
    {
        return $this->belongsTo('App\Lookup', 'loai_id', 'id');
    }
    public function linhVuc()
    {
        return $this->belongsTo('App\Lookup', 'linh_vuc_id', 'id');
    }
    public function coQuanBanHanh()
    {
        return $this->belongsTo('App\Lookup', 'co_quan_ban_hanh_id', 'id');
    }
}
