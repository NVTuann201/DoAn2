<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrgDefinedMapping extends Model
{
    protected $table = 'org_defined_mappings';
    protected $fillable = ['org_defined_format_id','field_order','field_name','data_dic_id','org_defined_field_id','selection_box_list','public','mandatory','form_page','field_set','element_group','description','examples'];
    public function orgDefinedField()
    {
        return $this->belongsTo('App\OrgDefinedField', 'org_defined_field_id', 'id')->withDefault();
    }
    public function orgDefinedFormat()
    {
        return $this->belongsTo('App\OrgDefinedFormat', 'org_defined_format_id', 'id')->withDefault();
    }
}
