<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NhomGen extends Model
{
    protected $guarded = [];
    public function phanLoai(){
        return $this->belongsTo('App\Lookup', 'phan_loai_id', 'id');
    }
}
