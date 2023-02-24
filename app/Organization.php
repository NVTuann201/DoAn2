<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Optix\Media\HasMedia;

class Organization extends Model
{
    use HasMedia;
    
    protected $table = 'organizations';
    protected $fillable = [
        'name_vietnamese',
        'name',
        'acronym',
        'acronym_vietnamese',
        'organization_type',
        'parent_organization_id',
        'description',
        'project_activities',
        'url',
        'address',
        'zipcode',
        'tel',
        'email',
        'contacts',
        'remarks',
        'portal_url_element',
        'portal_css',
        'portal_contents',
        'enabled',
        'id',
        'created_at',
        'logo_url',
        'updated_at'
    ];

    public function datasetResources() {
        return $this->hasMany('App\DatasetResource', 'organization_id');            
    }

    public function parent(){
        return $this->beLongsTo('App\Organization', 'parent_organization_id', 'id');
    }

    public function occurrences()
    {
        return $this->hasManyThrough('App\DarwinCoreOccurrences', 'App\DatasetResource', 'organization_id', 'dataset_resource_id', 'id', 'id');
    }

    public function mediable()
    {
        return $this->hasOne('App\Mediable','mediable_id')->where('mediable_type','App\Organization')->withDefault();
    }
}
