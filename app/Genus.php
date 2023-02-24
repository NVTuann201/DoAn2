<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Genus extends Model
{
    protected $table = 'genus';
    protected $fillable = ['name', 'name_vietnamese', 'notes', 'family_id', 'status', 'synonym_id', 'resource_id'];
    public $timestamps = false;

    public function family()
    {
        return $this->belongsTo('App\Family', 'family_id');
    }
    public function resource()
    {
        return $this->belongsTo('App\Resource');
    }

}
