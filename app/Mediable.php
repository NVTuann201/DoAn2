<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mediable extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table ='mediables';
    protected $fillable = [
        'media_id', 'mediable_id','mediable_type', 'group'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    
    public $timestamps = false;

    public function media()
    {
        return $this->belongsTo('App\Media', 'media_id')->withDefault();
    }
}
