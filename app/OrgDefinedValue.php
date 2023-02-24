<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrgDefinedValue extends Model
{
    protected $table = 'org_defined_values';
    protected $fillable = ['record_id','org_defined_field_id','value','id'];
    public function orgDefinedField()
    {
        return $this->belongsTo('App\OrgDefinedField', 'org_defined_field_id', 'id')->withDefault();
    }
}
