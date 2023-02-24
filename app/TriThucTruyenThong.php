<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TriThucTruyenThong extends Model
{
    protected $guarded = [];
    public function nhom(){
        return $this->belongsTo('App\Lookup', 'nhom_id', 'id');
    }
    public function nguonGen(){
        return $this->belongsToMany('App\NguonGen', 'nguon_gen_tri_thuc_truyen_thongs', 'tri_thuc_truyen_thong_id', 'nguon_gen_id');
    }
}
