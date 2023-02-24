<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $table = 'classes';
    protected $fillable = ['name', 'name_vietnamese', 'notes', 'phylum_id', 'status', 'synonym_id', 'resource_id'];
    public $timestamps = false;

    public function phylum()
    {
        return $this->belongsTo('App\PhyLum', 'phylum_id');
    }
    public function resource()
    {
        return $this->belongsTo('App\Resource');
    }
}
