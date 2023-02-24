<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrgDefinedFormat extends Model
{
    protected $table = 'org_defined_formats';
    protected $fillable = ['name','organization_id','core_table','id'];
    public function organization()
    {
        return $this->belongsTo('App\Organization', 'organization_id', 'id')->withDefault();
    }
    public $timestamps =false;

}
