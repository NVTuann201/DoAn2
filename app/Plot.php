<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plot extends Model
{
    protected $table = 'plots';
    protected $fillable = ['name', 'code', 'latitude', 'longitude', 'start_latitude', 'start_longitude', 'end_latitude', 'end_longitude','ecozone','shape','size','depth','elevation','administrative_local_name','description','transect_id','block_id'];
}
