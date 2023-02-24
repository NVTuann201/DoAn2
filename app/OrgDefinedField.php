<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrgDefinedField extends Model
{
    protected $table = 'org_defined_fields';
    protected $fillable = ['name','public','mandatory','id'];
}
