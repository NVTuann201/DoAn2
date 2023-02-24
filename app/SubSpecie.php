<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubSpecie extends Model
{
    protected $table = 'sub_species';

    protected $fillable = ['name', 'name_vietnamese', 'taxon_rank', 'species_id', 'notes', 'status', 'synonym_id', 'resource_id'];
    public $timestamps = false;
    public function resource()
    {
        return $this->belongsTo('App\Resource');
    }
}
