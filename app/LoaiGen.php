<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoaiGen extends Model
{
    protected $guarded = [];
    public function nhomGen(){
        return $this->belongsTo('App\NhomGen', 'nhom_gen_id', 'id');
    }

}
