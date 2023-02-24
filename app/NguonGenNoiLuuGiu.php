<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NguonGenNoiLuuGiu extends Model
{
    protected $guarded = [];
    public function noiLuuGius(){
        return $this->belongsTo('App\NoiLuuGiu', 'noi_luu_giu_id', 'id');
    }
}
