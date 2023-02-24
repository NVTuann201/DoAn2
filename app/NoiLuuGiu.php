<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Phaza\LaravelPostgis\Eloquent\PostgisTrait;

class NoiLuuGiu extends Model
{
    use PostgisTrait;
    protected $guarded = [];
    protected $postgisFields = [
        'geom',
    ];

}
