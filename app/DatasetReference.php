<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatasetReference extends Model
{
    protected $table = 'dataset_references';
    
    protected $fillable = ['name','dataset_resource_id','id'];

    public $timestamps =false;
    
    public function dataResource()
    {
        return $this->belongsTo('App\DatasetResource', 'dataset_resource_id', 'id')->withDefault();
    }
}
