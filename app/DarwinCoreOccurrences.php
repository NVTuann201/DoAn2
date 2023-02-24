<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DarwinCoreOccurrences extends Model
{
    protected $table = 'darwin_core_occurrences';

    protected $fillable = [
        'references',
        'institution_code',
        'owner_institution_code',
        'basis_of_record',
        'information_withheld',
        'data_generalizations',
        'dynamic_properties',
        'catalog_number',
        'occurrence_remarks',
        'record_number',
        'recorded_by',
        'individual_count',
        'sex',
        'life_stage',
        'reproductive_condition',
        'behavior',
        'establishment_means',
        'occurrence_status',
        'preparations',
        'disposition',
        'other_catalog_numbers',
        'previous_identifications',
        'associated_media',
        'associated_references',
        'associated_occurrences',
        'associated_sequences',
        'associated_taxa',
        'sampling_protocol',
        'sampling_effort',
        'event_date',
        'event_time',
        'year',
        'month',
        'day',
        'habitat',
        'field_number',
        'field_notes',
        'event_remarks',
        'continent',
        'water_body',
        'island_group',
        'island',
        'country',
        'municipality',
        'locality',
        'verbatim_elevation',
        'minimum_elevation_in_meters',
        'maximum_elevation_in_meters',
        'verbatim_depth',
        'minimum_depth_in_meters',
        'maximum_depth_in_meters',
        'minimum_distance_above_surface_in_meters',
        'maximum_distance_above_surface_in_meters',
        'location_according_to',
        'location_remarks',
        'decimal_latitude',
        'decimal_longitude',
        'geodetic_datum',
        'coordinate_uncertainty_in_meters',
        'coordinate_precision',
        'point_radius_spatialfit',
        'footprint_wkt',
        'footprint_srs',
        'footprint_spatial_fit',
        'georeferenced_by',
        'georeferenced_date',
        'georeference_protocol',
        'georeference_sources',
        'georeference_verification_status',
        'georeference_remarks',
        'earliest_eon_or_lowest_eonothem',
        'latest_eon_or_highest_eonothem',
        'earliest_era_or_lowest_erathem',
        'latest_era_or_highest_erathem',
        'earliest_period_or_lowest_system',
        'latest_period_or_highest_system',
        'earliest_epoch_or_lowest_series',
        'latest_epoch_or_highest_series',
        'earliest_age_or_lowest_stage',
        'latest_age_or_highest_stage',
        'lowest_biostratigraphic_zone',
        'highest_biostratigraphic_zone',
        'lithostratigraphic_terms',
        'group',
        'formation',
        'member',
        'bed',
        'identified_by',
        'date_identified',
        'identification_references',
        'identification_verification_status',
        'identification_remarks',
        'identification_qualifier',
        'scientific_name',
        'darwin_core_taxon_id',
        'district_id',
        'plot_id',
        'dataset_resource_id',
        'region_id',
        'province_id',
        'protected_area_id',
        'organization_id',
        'created_at',
        'updated_at',
        'site_identification',
        'diameter',
        'height',
        'health',
        'health_diagnosis',
        'sampling_method',
        'specimen_preservation',
        'water_temp',
        'weather',
        'ph_water',
        'ph_soil',
        'other',
        'verbatim_event_date',
        'temperature',
        'start_day_of_year',
        'end_day_of_year',
        'higher_geography',
        'identification_id',
        'type_status',
        'verbatim_coordinates',
        'verbatim_locality',
        'verbatim_latitude',
        'verbatim_longitude',
        'verbatim_srs',
        'verbatim_coordinate_system',
        'county', 'individual_id', 'type', 'modified', 'language', 'rights', 'rights_holder', 'access_rights', 'bibliographic_citation', 'institution_id', 'collection_id', 'collection_code'
    ];
    protected $casts = ['date_identified' => 'date:Y-m-d', 'georeferenced_date' => 'datetime:Y-m-d'];
    protected $dates = ['event_date',  'created_at', 'updated_at'];

    public function dataResource()
    {
        return $this->belongsTo('App\DatasetResource', 'dataset_resource_id', 'id')->withDefault();
    }
    public function darwinCoreTaxon()
    {
        return $this->belongsTo('App\DarwinCoreTaxon', 'darwin_core_taxon_id')->withDefault();
    }
    public function region()
    {
        return $this->belongsTo('App\Region', 'region_id', 'id')->withDefault();
    }
    public function province()
    {
        return $this->belongsTo('App\Province', 'province_id', 'id')->withDefault();
    }
    public function district()
    {
        return $this->belongsTo('App\District', 'district_id', 'id')->withDefault();
    }

    public function protectedArea()
    {
        return $this->belongsTo('App\ProtectedArea', 'protected_area_id', 'id')->withDefault();
    }
    public function plot()
    {
        return $this->belongsTo('App\Plot', 'plot_id', 'id')->withDefault();
    }
}
