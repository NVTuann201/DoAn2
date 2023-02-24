<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProtectedAreaTaxon extends Model
{
    protected $table = 'protected_area_taxons';
    protected $fillable = ['local_threat_status', 'provenance', 'remarks', 'protected_area_id', 'darwin_core_taxon_id', 'taxon_id'];

    public function DarwinCoreTaxon()
    {
        return $this->belongsTo('App\DarwinCoreTaxon', 'darwin_core_taxon_id', 'id')->withDefault();
    }

    public function ProtectedArea()
    {
        return $this->belongsTo('App\ProtectedArea', 'protected_area_id', 'id')->withDefault();
    }
}
