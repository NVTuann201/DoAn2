<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DatasetResource extends Model
{
    protected $table = 'dataset_resources';

    protected $fillable = [
        'uuid',
        'title',
        'publication_date',
        'language',
        'series',
        'abstract',
        'additional_info',
        'intellectual_rights',
        'distribution',
        'coverage',
        'website_url',
        'logo_url',
        'citation',
        'geographic_description',
        'keyword',
        'keyword_thesaurus',
        'begin_or_single_date',
        'end_date',
        'taxonomic_coverage',
        'west_bounding_coordinate',
        'east_bounding_coordinate',
        'north_bounding_coordinate',
        'south_bounding_coordinate',
        'original_filename',
        'stored_filename',
        'organization_id',
        'org_defined_format_id',
        'dataset_type',
        'status',
        'resource_id',
        'quan_huyen',
        'loai_doi_tuong',
        'doi_tuong_id'
    ];

    protected $dates = ['publication_date', 'begin_or_single_date', 'end_date', 'updated_at', 'created_at'];

    public function organization()
    {
        return $this->belongsTo('App\Organization', 'organization_id')->withDefault();
    }

    public function resource()
    {
        return $this->belongsTo('App\Resource', 'resource_id')->withDefault();
    }

    public function darwinCoreOccurrences()
    {
        return $this->hasMany('App\DarwinCoreOccurrences', 'dataset_resource_id');
    }

    public function darwinCoreTaxons()
    {
        return $this->hasMany('App\DarwinCoreTaxon', 'dataset_resource_id');
    }

    public function datasetReferences()
    {
        return $this->hasMany('App\DatasetReference', 'dataset_resource_id');
    }

    public function datasetResourceArea()
    {
        return $this->hasOne('App\DatasetResourceArea', 'dataset_resource_id');
    }

    public function scopeFilterStatusPublic($query)
    {
        return $query->where('status', 'public');
    }
    public function doiTuongBaoTon()
    {
      return $this->morphTo('doi_tuong_bao_ton','loai_doi_tuong','doi_tuong_id');
    }

}
