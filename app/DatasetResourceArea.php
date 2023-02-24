<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatasetResourceArea extends Model
{
    protected $fillable = [
        'dataset_resource_id',
        'protected_area_id',
    ];
    public $timestamps =false;
    public function datasetResource()
    {
        return $this->belongsTo('App\DatasetResource', 'dataset_resource_id', 'id');
    }

    public function protectedArea()
    {
        return $this->belongsTo('App\ProtectedArea', 'protected_area_id', 'id');
    }
}
