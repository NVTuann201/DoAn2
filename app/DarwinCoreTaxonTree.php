<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DarwinCoreTaxonTree extends Model
{
    protected $table = 'darwin_core_taxon_tree';

    public function DarwinCoreTaxon()
    {
        return $this->hasMany('App\DarwinCoreTaxon', 'species_id', 'species_id');
    }

}
