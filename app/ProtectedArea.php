<?php

namespace App;

use App\Scopes\HaNoiScope;
use Illuminate\Database\Eloquent\Model;
use Optix\Media\HasMedia;

class ProtectedArea extends Model
{
    use HasMedia;
    public function registerMediaGroups()
    {
        $this->addMediaGroup('default')
            ->performConversions('thumbnail');
    }

    protected $casts = [
        'geometry' => 'array',
    ];

    protected $table = 'protected_areas';

    protected $fillable = [
        'geometry',
        'wdpaid',
        'wdpa_pid',
        'name',
        'orig_name',
        'country',
        'sub_loc',
        'desig',
        'desig_eng',
        'desig_type',
        'iucncat',
        'marine',
        'rep_m_area',
        'rep_area',
        'status',
        'status_year',
        'gov_type',
        'mang_auth',
        'int_crit',
        'mang_plan',
        'metadataid',
        'longitude',
        'latitude',
        'legislation',
        'description',
        'isInternational',
        'name_vn',
        'name_en',
        'international_criteria',
        'biodiversity_level',
        'management_type',
        'region_id',
        'm_area',
        'status_no',
        'plan_in',
        'change',
        'province_plan',
        'purpose',
        'management_proposal',
        'international_criteria',
        'habitat_types',
        'unique_flora',
        'unique_fauna',
        'biological_richness',
        'quan_huyen',
        'dia_chi',
        'ngay_ban_hanh',
        'co_quan_ban_hanh',
        'files',
        'co_quan_quan_ly',
        'ke_hoach_quan_ly',
        'dien_tich_vung_loi',
        'dien_tich_vung_dem'
    ];

    public $timestamps = true;

    public function protectedAreaTaxons()
    {
        return $this->hasMany('App\ProtectedAreaTaxon');
    }

    public function datasetResourceAreas()
    {
        return $this->hasMany('App\DatasetResourceArea', 'protected_area_id');
    }

    public function datasetResources()
    {
        return $this->belongsToMany('App\DatasetResource', 'dataset_resource_areas', 'protected_area_id', 'dataset_resource_id');
    }

    public function region()
    {
        return $this->belongsTo('App\Region');
    }
    public function heSinhThai()
    {
        return $this->hasMany(HeSinhThai::class, 'dia_diem_id');
    }
}
