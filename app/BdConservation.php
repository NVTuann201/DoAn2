<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BdConservation extends Model
{
    protected $table = 'bd_conservations';
    protected $fillable = ['type','name','description','bd_conservation_references','organization_id'];
    public function orgDefinedField()
    {
        return $this->belongsTo('App\OrgDefinedField', 'org_defined_field_id', 'id')->withDefault();
    }
}
