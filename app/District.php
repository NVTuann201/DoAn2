<?php

namespace App;

use App\Scopes\QuanHaNoiScope;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'districts';
    protected $fillable = ['name','name_vietnamese','id','administrative_code','province_id'];
    public $timestamps =false;
    public function Province()
    {
        return $this->belongsTo('App\Province', 'province_id', 'id')->withDefault();
    }
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new QuanHaNoiScope());
    }
}
