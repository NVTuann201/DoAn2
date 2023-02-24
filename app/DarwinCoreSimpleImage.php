<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Optix\Media\HasMedia;

class DarwinCoreSimpleImage extends Model
{
    use HasMedia;
    public function registerMediaGroups()
    {
        $this->addMediaGroup('default')
            ->performConversions('thumbnail');
    }
    protected $table = 'darwin_core_simple_images';

    protected $fillable = [
        'id',
        'identifier',
        'references',
        'title',
        'description',
        'spatial',
        'latitude',
        'longitude',
        'format',
        'created',
        'creator',
        'contributor',
        'publisher',
        'audience',
        'license',
        'rights_holder',
        'content_type',
        'data',
        'darwin_core_occurrence_id',
        'darwin_core_taxon_id',
        'other_relation',
        'public',
        'subject_id',
        'subject_type',
    ];

    protected $casts = ['created' => 'date:Y-m-d'];

    public function darwinCoreOccurrence()
    {
        return $this->belongsTo('App\DarwinCoreOccurrences', 'darwin_core_occurrence_id', 'id')->withDefault();
    }

    public function darwinCoreTaxon()
    {
        return $this->belongsTo('App\DarwinCoreTaxon', 'darwin_core_taxon_id', 'id')->withDefault();
    }
    public function subject()
    {
        return $this->morphTo();
    }
}
