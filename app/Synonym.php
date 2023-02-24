<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Synonym extends Model
{
    protected $table = 'synonyms';
    protected $fillable = ['name', 'source', 'description', 'darwin_core_taxon_id', 'taxon_id'];
    public function DarwinCoreTaxon()
    {
        return $this->belongsTo('App\DarwinCoreTaxon', 'darwin_core_taxon_id', 'id')->withDefault();
    }
}
