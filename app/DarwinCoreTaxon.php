<?php

namespace App;

use App\Traits\HasSimpleImage;
use Illuminate\Database\Eloquent\Model;

class DarwinCoreTaxon extends Model
{
    use HasSimpleImage;
    protected $table = 'darwin_core_taxons';

    protected $fillable = [
        'scientific_name_id',
        'accepted_name_usage_id',
        'parent_name_usage_id',
        'original_name_usage_id',
        'name_according_to_id',
        'name_published_in_id',
        'taxon_concept_id',
        'scientific_name',
        'scientific_name_style',
        'accepted_name_usage',
        'parent_name_usage',
        'original_name_usage',
        'name_according_to',
        'name_published_in',
        'name_published_in_year',
        'higher_classification',
        'phylum',
        'class',
        'order',
        'family',
        'genus',
        'subgenus',
        'specific_epithet',
        'infraspecific_epithet',
        'taxon_id',
        'taxon_rank',
        'verbatim_taxon_rank',
        'scientific_name_authorship',
        'vernacular_name',
        'vernacular_name_english',
        'vernacular_name_others',
        'nomenclatural_code',
        'taxonomic_status',
        'nomenclatural_status',
        'taxon_remarks',
        'modified',
        'language',
        'rights',
        'rights_holder',
        'access_rights',
        'bibliographic_citation',
        'references',
        'information_withheld',
        '_search',
        'kingdom_id',
        'phylum_id',
        'class_id',
        'order_id',
        'family_id',
        'genus_id',
        'species_id',
        'sub_species_id',
        'dataset_resource_id',
        'resource_id',
    ];

    public function datasetResource()
    {
        return $this->dataResource();
    }
    public function dataResource()
    {
        return $this->belongsTo('App\DatasetResource', 'dataset_resource_id', 'id')->withDefault();
    }
    public function nbdsTaxonExtension()
    {
        return $this->hasMany('App\NbdsTaxonExtension', 'darwin_core_taxon_id');
    }
    public function nbdsTaxonExtensionFirst()
    {
        return $this->hasOne('App\NbdsTaxonExtension', 'darwin_core_taxon_id')->orderBy('updated_at', 'desc');
    }
    public function kingDom()
    {
        return $this->belongsTo('App\KingDom', 'kingdom_id', 'id')->withDefault();
    }
    public function phylum()
    {
        return $this->belongsTo('App\PhyLum', 'phylum_id', 'id')->withDefault();
    }
    function class ()
    {
        return $this->belongsTo('App\Classes', 'class_id', 'id')->withDefault();
    }
    public function order()
    {
        return $this->belongsTo('App\Order', 'order_id', 'id')->withDefault();
    }
    public function family()
    {
        return $this->belongsTo('App\Family', 'family_id', 'id')->withDefault();
    }
    public function genus()
    {
        return $this->belongsTo('App\Genus', 'genus_id', 'id')->withDefault();
    }
    public function species()
    {
        return $this->belongsTo('App\Species', 'species_id', 'id')->withDefault();
    }
    public function subSpecies()
    {
        return $this->belongsTo('App\SubSpecie', 'sub_species_id', 'id')->withDefault();
    }
    public function synonyms()
    {
        return $this->hasMany('App\Synonym', 'darwin_core_taxon_id')->orderBy('id');
    }
    public function protectedAreaTaxon()
    {
        return $this->hasMany('App\ProtectedAreaTaxon')->orderBy('id');
    }
    public function darwinCoreOccurrences()
    {
        return $this->hasOne('App\DarwinCoreOccurrences');
    }
}
