<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Species extends Model
{

    protected $table = 'species';
    protected $fillable = ['name', 'name_vietnamese', 'notes', 'genus_id', 'taxon_rank', 'status', 'synonym_id', 'resource_id'];
    public $timestamps = false;

    public function genus()
    {
        return $this->belongsTo('App\Genus', 'genus_id');
    }
    public function darwinCoreTaxons()
    {
        return $this->hasMany('App\DarwinCoreTaxon', 'species_id');
    }
    public function resource()
    {
        return $this->belongsTo('App\Resource');
    }
    public function synonym(){
        return $this->belongsTo('App\Species', 'synonym_id', 'id');
    }
}
