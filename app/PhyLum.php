<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhyLum extends Model
{
    protected $table = 'phylums';

    protected $fillable = ['name', 'name_vietnamese', 'notes', 'kingdom_id', 'status', 'synonym_id', 'resource_id'];

    public $timestamps = false;

    public function darwinCoreTaxons()
    {
        return $this->hasMany('App\DarwinCoreTaxon', 'phylum_id');
    }

    public function kingDom()
    {
        return $this->belongsTo('App\KingDom', 'kingdom_id');
    }
    public function resource()
    {
        return $this->belongsTo('App\Resource');
    }

}
