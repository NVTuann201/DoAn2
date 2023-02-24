<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NbdsTaxonExtension extends Model
{
    protected $table = 'nbds_taxon_extensions';
    protected $fillable = [
        'other_vietnamese_law_to_protect_species',
        'cites',
        'county',
        'provenance_in_vietnam',
        'naturalness',
        'invasive_status',
        'sensitivity',
        'rarity',
        'use',
        'morphological_description',
        'distribution_status_in_vietnam',
        'local_endemism',
        'classification_of_behavioral_features',
        'distribution_in_vietnam',
        'distribution_in_the_world',
        'protection_measures',
        'reproductive_time',
        'reproductive_form',
        'development_time',
        'food',
        'reproduction',
        'other_biological_characteristics',
        'habitat',
        'ecological_characteristics',
        'other_information_related_to_species',
        'darwin_core_taxon_id',
        'taxon_id',
        'mangrove',
        'lifeform',
        'red_list',
        'iucn_2012',
        'provenance_in_local',
        'natural', // cho biết loài sống tự nhiên hoang dã, hay thuần chủng ...
        'environmentally_sensitive', // Độ nhạy cảm với môi trường
        'rich_and_rare',
        'note',
        'distribution_in_local',
        'population',
        'base_origin',
        'cultivation_purpose',
        'pressure',
        'competing_species',
        'symbiotic_species',
        'protected',
        'other_porotectec',
        'protective_measures',
        'benefit',
        'other',
        'nghi_dinh',
        'phu_luc_nghi_dinh',
        'phan_hang_sach_do',
        'phan_bo_ha_noi'
    ];
    public function DarwinCoreTaxon()
    {
        return $this->belongsTo('App\DarwinCoreTaxon', 'darwin_core_taxon_id', 'id')->withDefault();
    }
    public function getDateFormat()
    {
        return 'Y-m-d H:i:s.u';
    }
}
