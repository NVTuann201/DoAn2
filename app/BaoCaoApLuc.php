<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaoCaoApLuc extends Model
{
    protected $guarded = [];
    public function phanCap(){
        return $this->belongsTo('App\Lookup', 'phan_cap_id', 'id');
    }
    public function chiThi(){
        return $this->belongsTo('App\ChiThi', 'chi_thi_id', 'id');
    }
}
