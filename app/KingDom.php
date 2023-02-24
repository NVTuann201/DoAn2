<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KingDom extends Model
{
    protected $table = 'kingdoms';
    protected $fillable = ['name', 'name_vietnamese', 'notes', 'status', 'synonym_id', 'resource_id'];
    public $date = ['created_at', 'updated_at'];

    public function darwinCoreTaxons()
    {
        return $this->hasMany('App\DarwinCoreTaxon', 'kingdom_id');
    }
    public function resource()
    {
        return $this->belongsTo('App\Resource');
    }
}
