<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lookup extends Model
{
    protected $guarded = [];
    public function heSinhThai()
    {
        return $this->hasMany(HeSinhThai::class, 'he_sinh_thai_lookup_id');
    }
}
